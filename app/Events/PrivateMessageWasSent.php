<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\MsgIn;

class PrivateMessageWasSent implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;
    public $msg;
    public $body; 

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(MsgIn $msg, $body)
    {
        $this->msg = $msg;
        $this->body = $body;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PresenceChannel('chat.'.$this->msg->link);
    }

    public function broadcastAs()
    {
        return 'test';
    }

    public function broadcastWith()
    {
        return $this->body;
    }


}
