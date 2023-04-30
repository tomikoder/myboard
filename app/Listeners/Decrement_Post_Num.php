<?php

namespace App\Listeners;

use App\Events\PostWasRemoved;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class Decrement_Post_Num
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
     * @param  PostWasRemoved  $event
     * @return void
     */
    public function handle(PostWasRemoved $event)
    {
        $user = $event->user;
        $user->num_of_posts -= 1;
        $user->save();
    }
}
