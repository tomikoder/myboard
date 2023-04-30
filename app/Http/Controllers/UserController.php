<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Events\NotificationWasRead;
use App\Events\PrivateMessageWasSent;
use App\User;
use App\Events\MessageWasSent;
use App\Events\PostWasRemoved;
use App\Notification;
use App\MsgIn;
use App\Post;
use App\UserAD;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    static private function data_to_js() {
        $user_data = array();
        if (!Auth::guest()) {
            $user_data['user_id'] = Auth::user()->id;
            $user_data['notify_count'] = Notification::where('receiver', '=', Auth::user()->id)
            ->where('readed', '=', FALSE)
            ->count();
        }
        return $user_data;
    }

    public static $A = 1000;
 
    public function user_notifications(Request $request) 
    {
        $to_update = array();
        $notify = Notification::where('receiver', '=', Auth::user()->id)->orderBy("created_at", "DESC")->get()->toArray(); 
        $slice = 10;     
        $page = $request->get('page') ?? 1;
        $notify_pag = array_slice($notify, $slice * ($page  - 1), $slice, true);
        foreach ($notify_pag as $n) {
            if (!$n['readed']) {
                array_push($to_update, $n['_id']);
            }
        }
        $notify_pag = new LengthAwarePaginator($notify_pag, count($notify), $slice, $page,  [
                            'path' => $request->url(),
                            'query' => $request->query(),
                        ]);
                
        event(new NotificationWasRead($to_update));                                            
        
        return view('notify', ['title' => 'NOTIFICATIONS', 'user_data' => self::data_to_js(),
                    'results' => $notify_pag,]);
    }

    public function user_public_panel(Request $request, User $user)
    {
        if ($request->user() && $request->user()->id == $user->id) return redirect()->route('private_panel');
        return view('user_panel', ['title' => 'USER PANEL', 'user_data' => self::data_to_js(),
                    'user' => $user,]);
    }

    public function user_private_panel(Request $request)
    {
        
        return view('user_panel_private', ['title' => 'You panel', 'user_data' => self::data_to_js(),
        'user' => $request->user()]);

    }

    public function send_private_message(Request $request, User $user) {
        $this->validate($request, ['text' => 'required',]);
        $data = array('from' => $request->user()->name, 'text' => $request->text, "to" => $user->name);                            
        $new_message =  MsgIn::create(['receiver' => $user->id, 'body' => $data, 'sender' => $request->user()->id]);
        event(new MessageWasSent($new_message, $user, $request->user()));              
        return response()->json(["msg" => "OK"]);  
    }

    private function traverse_response($curr_response, $new_body) {
        if (!$curr_response) return $new_body;
        $curr_response["response"] = self::traverse_response($curr_response["response"], $new_body);
        return $curr_response;
    }

    public function send_private_message_reply(Request $request, MsgIn $message) {

        $this->validate($request, ['text' => 'required',]);
        $body = ["from" => $request->user()->name, "from_user_id" => $request->user()->id,
                 "text"=> $request->text, 
                 "date" => ''.date('M d, Y, G:i', strtotime(Carbon::now())),
                 "response" => null
                ];
        if (!$message->response) {
            $message->response = $body;
            $message->save();            
        }
        else {
            $message->response = self::traverse_response($message->response, $body);
            $message->save();
        }

        broadcast(new  PrivateMessageWasSent($message, $body))->toOthers();
        if ($request->flag) {
            $user_id = (int) $request->flag;
            if ($user_id == $message->receiver) {
                $user_id = $message->sender;
            }
            else {
                $user_id = $message->receiver;
            }
            $user = User::where("id", "=", $user_id)->first();
            event(new MessageWasSent($message, $user, $request->user()));              
        }
        return response()->json(["msg" => $request->flag]);

    }

    public function read_private_message(Request $request, MsgIn $message) {
        $this->authorize('view', $message, MsgIn::class);
        $user_data = self::data_to_js();
        $user_data["msg_code"] = $message->link;
        return view('private_message', ['title' => 'Private message', 'user_data' => $user_data,
                    'user' => $request->user(), 'msg' => $message,]);
    }

    public function list_your_private_messages(Request $request) {
        $data = MsgIn::where("sender", $request->user()->id)->get()->toArray();
        return view('list_your_messages', ['title' => 'You messages', 'user_data' => self::data_to_js(),
        'user' => $request->user(), 'data' => $data,]);
    }

    public function admin_panel(Request $request) {
        if (!$request->user()->is_super_user) abort(401, "You don't have admin privileges");
        $users = User::select('name', 'id', 'is_active', 'is_baned')->get();
        $posts = Post::select('title', 'id', 'is_closed', 'user_id')->get();
        return view('admn_panel', ['title' => 'ADMIN PANEL', 'user_data' => self::data_to_js(), 'users' => $users,
                                   'posts' =>  $posts]);
    }

    public function admin_panel_posts_action(Request $request) {
        $post = $request->posts;
        $action = $request->action;
        preg_match_all('!\d+!', $post, $matches);

        if ($action == "close") {
            DB::table('posts')->where("id", "=", $post)->update(['is_closed' => TRUE]);
        }
        elseif ($action == "delete") {
            DB::table('posts')->where("id", "=", $matches[0][0])->delete();
            DB::table('users')->where("id", "=", $matches[0][1])->decrement('num_of_posts', 1);
        }
        DB::commit();
        return redirect()->route("admin_panel");
    }

    public function admin_panel_user_action(Request $request) {
        $user = $request->users;
        $action = $request->action;
        if ($action == "delete") {
            DB::table('users')->where("id", "=", $user)->update(['is_active' => FALSE]);
        }
        elseif ($action == "add_ban") {
            DB::table('users')->where("id", "=", $user)->update(['is_baned' => TRUE]);
        }
        elseif ($action == "remove_ban") {
            DB::table('users')->where("id", "=", $user)->update(['is_baned' => FALSE]);
        }
        elseif ($action == "add_again") {
            DB::table('users')->where("id", "=", $user)->update(['is_active' => TRUE]);
        }
        DB::commit();

        return redirect()->route("admin_panel");
    }

    public function change_pass(Request $request) {
        $user_name = $request->user()->name;
        $old_pass = $request->old_pass;
        $new_pass = $request->new_pass;
        $try_login = Auth::attempt(["name" => $user_name, "password" => $old_pass]);
        
        if ($try_login) {
            $user = $request->user(); 
            $user->password = Hash::make($request->new_pass);
            $user->save();
    

            return response()->json(["msg" => "OK"]);
        }
        return response()->json(["msg" => "Bad password"], 500); 
    }

    public function remove_account(Request $request) {
        $user_name = $request->user()->name;
        $pass = $request->password;
        $try_login = Auth::attempt(["name" => $user_name, "password" => $pass]);
        if ($try_login) {
            $request->is_active = FALSE;
            Auth::logout();
            return response()->json(["msg" => "OK"]); 
        }
        return response()->json(["msg" => "Bad password"], 500); 
    }
}
