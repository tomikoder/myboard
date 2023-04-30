<?php

namespace App\Listeners;

use App\Events\PostWasComment2;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notification;

class NotfifyPostUser2
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
     * @param  PostWasComment2  $event
     * @return void
     */
    public function handle(PostWasComment2 $event)
    {
        $data = [
            'post_id' => $event->post->id,
            'post_link' => $event->post->link,
            'user_name' => $event->user->name,
            'comment_id' => $event->comment->id,
            'date' => $event->comment->created_at,
            'comment_text' => $event->comment->text,
        ];
        $new_notify = Notification::create(['type' => 'Post comment', 'receiver' => $event->post->user_id, 'readed' => FALSE,
                                              'data' => $data]  
                                                );
    }
}
