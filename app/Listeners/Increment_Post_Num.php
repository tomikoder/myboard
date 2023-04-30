<?php

namespace App\Listeners;

use App\Events\PostWasAdded;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class Increment_Post_Num
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
     * @param  PostWasAdded  $event
     * @return void
     */
    public function handle(PostWasAdded $event)
    {
        $user = $event->user;
        $user->num_of_posts += 1;
        $user->save();
    }
}
