<?php

namespace App\Listeners;

use App\Events\PostWasLiked;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class IncreaseNumOfLikes
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
     * @param  PostWasLiked  $event
     * @return void
     */
    public function handle(PostWasLiked $event)
    {
        $post = $event->post;
        $post->num_of_likes++;
    }
}
