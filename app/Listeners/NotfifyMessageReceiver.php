<?php

namespace App\Listeners;

use App\Events\MessageWasSent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notification;

class NotfifyMessageReceiver
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
     * @param  MessageWasSent  $event
     * @return void
     */
    public function handle(MessageWasSent $event)
    {
        $data = [
            'msg_link' => $event->msg->link,
            'user_name' => $event->sender->name,
            'date' => $event->msg->created_at,
        ];
        $new_notify = Notification::create(['type' => 'New Message', 'receiver' => $event->receiver->id, 'readed' => FALSE,
                                              'data' => $data]  
                                                );
    }
}
