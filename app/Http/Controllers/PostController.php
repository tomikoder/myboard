<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Post;
use App\Comment;
use App\User;
use App\Events\PostWasAdded;
use App\Events\PostWasRemoved;
use App\Events\PostWasLiked;
use App\Notifications\PostWasLikedNotify;
use App\Events\PostWasComment2;
use App\Notification;
use App\Events\CommentWasComment;

class PostController extends Controller
{
    static private function data_to_js() {
       $user_data = array();
       if (!Auth::guest()) {
           $user_data['user_id'] = Auth::user()->id;
           $user_data['notify_count'] = Notification::where('receiver', '=', Auth::user()->id)
                                                        ->where('readed', '=', FALSE)
                                                        ->count();
       }
       $user_data['additional_script'] = null;
       return $user_data;
   }
       
   public function index(Request $request) 
    {
        $user_data = self::data_to_js();
        


        $posts = DB::table('posts')
                                    ->select('posts.updated_at', 'posts.title', 'posts.text', 'posts.id as post_id', 'posts.link',
                                             'users.id as user_id', 'users.name', DB::raw('COUNT(comments.id) AS num_of_comm'),
                                             'posts.num_of_likes', 'posts.likes')
                                    ->join('users', 'users.id', '=', 'posts.user_id')
                                    ->leftJoin('comments', 'posts.id', '=', 'comments.post_id')
                                    ->groupBy('posts.id')
                                    ->orderBy('posts.created_at', 'desc')
                                    ->get()->toArray();

        if ($request->user()) {
            foreach ($posts as $p) {
                $d = json_decode($p->likes, TRUE);
                $d = json_decode($d, TRUE);
                if (in_array($request->user()->id, $d['users_id'])) {
                    $p->you_like = TRUE;
                }
                else {
                    $p->you_like = FALSE;
                } 
            }                            
        }
                
        $slice = 4;     
        $total = count($posts);                       
        $page = $request->get('page') ?? 1;
        $posts = array_slice($posts, $slice * ($page  - 1), $slice, true);                             
        $posts_pag = new LengthAwarePaginator($posts, $total, $slice, $page,  [
            'path' => $request->url(),
            'query' => $request->query(),
        ]);                            

        return view('main', ['title' => 'menu',
                             'posts_pag' => $posts_pag,
                             'user_data' => $user_data,
                            ]);
    }

    public function store(Request $request) 
    {
        $this->authorize('create', Post::class);
        $this->validate($request, ['title' => 'required|max:20',
                                    'text' => 'required',]);
        $request->user()->posts()->create(['title' => $request->title,
                                           'text'  => $request->text,]);
        event(new PostWasAdded($request->user()));    
        return redirect('/');
    }

    public function destroy(Request $request, Post $post) {
        $this->authorize('delete', $post, Post::class);
        $post->delete();
        event(new PostWasRemoved($request->user()));    
        return response()->json([]);    
    }

    private static function traverse_com($curr_comm, $comments) {
        if (empty($comments)) return;
        $c = 0;
        while (!empty($comments) && $c < count($comments)) {
            if ($comments[$c]->par_comm == $curr_comm->id) {
                array_push($curr_comm->sub_comm, $comments[$c]);
                array_splice($comments, $c, 1);
            }
            else {
                $c++;
            }
        }
        foreach ($curr_comm->sub_comm as $curr_comm2) {
            self::traverse_com($curr_comm2, $comments);
        } 
    }


    public function detail(Request $request, Post $post) {
        $user_data = self::data_to_js();

        $d = json_decode($post->likes, TRUE);
        if ($request->user() && $request->user()->id != $post->user_id) {
            if (in_array($request->user()->id, $d['users_id'])) $post->you_like = TRUE;
            else $post->you_like = FALSE;    
        }

        $comments = DB::table('comments')                                    
                                 ->select('comments.id', 'comments.text', 'users.name', 'comments.created_at',
                                          'comments.updated_at', 'comments.par_comm', 'users.id as user_id',)
                                 ->join('users', 'users.id', '=', 'comments.user_id')
                                 ->orderBy('comments.created_at', 'desc')
                                 ->where('comments.post_id', '=', $post->id)
                                 ->get()->toArray(); 
        
        foreach ($comments as $c) {
            $c->sub_comm = array();
        }                                 
        
        $nested_comments = array();

        $c = 0;
        while (!empty($comments) && $c < count($comments)) {
            if ($comments[$c]->par_comm == 0) {
                array_push($nested_comments, $comments[$c]);
                array_splice($comments, $c, 1);
            }
            else {
                $c++;
            }
        }
        
        foreach ($nested_comments as $curr_comm) {
            self::traverse_com($curr_comm, $comments);
        }
                
        return view('detail', ['title' => $post->title, 'post' => $post, 'comments' => $nested_comments, 
                               'user_data' => $user_data,]);
    }

    public function update(Request $request, Post $post) {
        $this->authorize('update', $post, Post::class);
        $this->validate($request, ['title' => 'max:20']);
        $post->title = $request->title;
        $post->text  = $request->text;
        $post->save();
        return response()->json([]);
    }

    public function commentAdd(Request $request, Post $post) {
        $this->authorize('create', Comment::class);
        $this->validate($request, [
            'comm' => 'min:3|max:200|required'
        ]);
        

        $comment = new Comment(['parent_title' => $post->title, 'text' => $request->comm]);
        $request->user()->comments()->save($comment);
        $post->comments()->save($comment);
        $comment = $comment->fresh();
            
        if ($post->user_id != $request->user()->id) event(new PostWasComment2($post, $comment, $request->user()));
        return response()->json(["comment_id"   => $comment->id,
                                 "user_id" => $request->user()->id,
                                 "comment_date" => date('M d, Y, G:i', strtotime($comment->created_at)).'',
                                 "comment_auth" => $request->user()->name]);
    }
    
    public function commentDel(Request $request, Comment $comment) {
        $this->authorize('delete', $comment, Comment::class);
        $comment->delete();
        return response()->json([]);
    }

    public function commentEdit(Request $request, Comment $comment) {
        $this->authorize('update', $comment, Comment::class);
        $this->validate($request, ['comm' => 'min:3|max:200|required']);
        $comment->text = $request->comm;
        $comment->save();
        $comment = $comment->fresh();
        $new_date = date('M d, Y, G:i', strtotime($comment->created_at)).' Updated at '.date('M d, Y, G:i', strtotime($comment->updated_at));
        return response()->json(['comm' => $comment->text,
                                 'new_date' => ''.$new_date,]);
    }

    public function commentReply(Request $request, Comment $comment, Post $post) {
        $this->authorize('create', Comment::class);
        $this->validate($request, ['comm' => 'min:3|max:200|required']);
        $new_comment = new Comment(['parent_title' => $post->title, 'text' => $request->comm, 'par_comm' => $comment->id]);
        $request->user()->comments()->save($new_comment);
        $post->comments()->save($new_comment);
        $new_comment = $new_comment->fresh();
    
        event(new CommentWasComment($post, $new_comment, $comment->user));
        if ($post->user->id != $request->user()->id) {
            event(new PostWasComment2($post, $comment, $request->user()));
        }

        return response()->json([  
                "comment_auth" => $request->user()->name,
                "comment_date" => date('M d, Y, G:i', strtotime($new_comment->created_at)).'',
                "user_id" => $request->user()->id, 
                "comment_id"   => $new_comment->id,]);
    }

    public function postLike(Request $request, Post $post) {
        $this->authorize('add_like', $post, Post::class);        
        $data = json_decode($post->likes, TRUE);
        $action = (int) $request->liked;



        if (!$action) {
            array_push($data['users_id'], $request->user()->id); 
            $post->likes = json_encode($data);
            $post->num_of_likes++;
        }
        else {
            $indx = array_search($request->user()->id, $data['users_id']);
            array_splice($data['users_id'], $indx, 1);
            $post->likes = json_encode($data);
            $post->num_of_likes--;
        }
        $post->save(); 

        return response()->json(["msg"]);

    }


    public function search(Request $request) {
        $words = preg_split('/\s+/', $request->input('q'));
        $to_search = "";
        $user_data = self::data_to_js();
        
        foreach ($words as $w) {
            $to_search .= "$w ";
        }
        
        $posts =       DB::table('posts')->select('posts.updated_at', 'posts.title', 'posts.text', 'posts.id as post_id',
                                                       'posts.link', DB::raw("MATCH(posts.title, posts.text) AGAINST('$to_search') AS score"),
                                                       'users.id as user_id', 'users.name', DB::raw("TRUE as flag")  
                                                      )
                                              ->whereRaw("MATCH(posts.title, posts.text) AGAINST('$to_search')")
                                              ->join('users', 'users.id', '=', 'posts.user_id');
          
                    
                                                      ;                           
        $results = DB::table('comments')->select('comments.updated_at', 'comments.parent_title as title', 'comments.text', 'comments.id as comm_id',
                                                       'posts.link', DB::raw("MATCH(comments.parent_title, comments.text) AGAINST('$to_search') AS score"),
                                                       'users.id as user_id', 'users.name', DB::raw("FALSE as flag") 
                                                      )
                                              ->whereRaw("MATCH(comments.parent_title, comments.text) AGAINST('$to_search')")
                                              ->join('users', 'users.id', '=', 'comments.user_id')
                                              ->join('posts', 'posts.id', '=', 'comments.post_id')
                                              ->union($posts)
                                              ->orderBy('score')
                                              ->get()
                                              ->toArray();
        
        $slice = 4;     
        $page = $request->get('page') ?? 1;
        $results_pag = array_slice($results, $slice * ($page  - 1), $slice, true);                             
        $results_pag = new LengthAwarePaginator($results_pag, count($results), $slice, $page,  ['path' => $request->url(), 'query' => $request->query(),]);                            
                                                                            
        return view('search_result', [
                                      'user_data' => $user_data,
                                      'results'    => $results_pag,
                                      'title'    => 'SEARCH RESULT'
                                     ]);
    }

    public function user_posts(Request $request, User $user) {
        $user_data = self::data_to_js();
        $posts =       DB::table('posts')->select('posts.updated_at', 'posts.title', 'posts.text', 'posts.id as post_id',
                                                       'posts.link', 'users.id as user_id', 'users.name', DB::raw("TRUE as flag")  
                                                      )
                                              ->where('users.id', '=', $user->id)
                                              ->join('users', 'users.id', '=', 'posts.user_id');
          
                    
                                                      ;                           
        $results = DB::table('comments')->select('comments.updated_at', 'comments.parent_title as title', 'comments.text', 'comments.id as comm_id',
                                                       'posts.link', 'users.id as user_id', 'users.name', DB::raw("FALSE as flag") 
                                                )
                                              ->where('users.id', '=', $user->id)
                                              ->join('users', 'users.id', '=', 'comments.user_id')
                                              ->join('posts', 'posts.id', '=', 'comments.post_id')
                                              ->union($posts)
                                              ->orderBy('updated_at', 'DESC')
                                              ->get()
                                              ->toArray();
        
        $slice = 4;     
        $page = $request->get('page') ?? 1;
        $results_pag = array_slice($results, $slice * ($page  - 1), $slice, true);                             
        $results_pag = new LengthAwarePaginator($results_pag, count($results), $slice, $page,  ['path' => $request->url(), 'query' => $request->query(),]);                            
                                                                            
        return view('search_result', [
                                      'user_data' => $user_data,
                                      'results'    => $results_pag,
                                      'title'    => 'YOUR POSTS'
                                    ]);
    }
}
