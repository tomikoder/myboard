<?php

namespace App\Listeners;

use App\Events\PrivateMessageWasSent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotfifyPrivateMessageReceiver
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
     * @param  PrivateMessageWasSent  $event
     * @return void
     */
    public function handle(PrivateMessageWasSent $event)
    {
        //
    }
}
