<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\MsgIn;
use App\User;

class MessageWasSent implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;
    public $msg;
    public $receiver;
    public $sender;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(MsgIn $msg, User $receiver, User $sender)
    {
        $this->msg = $msg;
        $this->receiver = $receiver;
        $this->sender = $sender;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-'.$this->receiver->id);
    }

    public function broadcastAs()
    {
    return 'test';
    }

}
