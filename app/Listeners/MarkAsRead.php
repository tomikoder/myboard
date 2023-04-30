<?php

namespace App\Listeners;

use App\Events\NotificationWasRead;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notification;

class MarkAsRead
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
     * @param  NotificationWasRead  $event
     * @return void
     */
    public function handle(NotificationWasRead $event)
    {
        Notification::whereIn('_id', $event->notifications)
                ->update(['readed' => TRUE]);
    }
}
