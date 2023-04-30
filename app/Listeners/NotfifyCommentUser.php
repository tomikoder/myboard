<?php

namespace App\Listeners;

use App\Events\CommentWasComment;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notification;

class NotfifyCommentUser
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  CommentWasComment  $event
     * @return void
     */
    public function handle(CommentWasComment $event)
    {        
        $data = [
            'post_id' => $event->post->id,
            'post_link' => $event->post->link,
            'user_name' => $event->comment->user->name,
            'comment_id' => $event->comment->id,
            'date' => $event->comment->created_at,
            'comment_text' => $event->comment->text,
        ];
        $new_notify = Notification::create(['type' => 'Post comment', 'receiver' => $event->user->id, 'readed' => FALSE,
                                              'data' => $data]  
                                                );
    }
}
