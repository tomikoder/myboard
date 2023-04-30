<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Post;
use App\Comment;
use App\User;

class CommentWasComment implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;
    public $post;
    public $comment;
    public $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Post $post, Comment $comment, User $user)
    {
        $this->post = $post;
        $this->comment = $comment;
        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-'.$this->user->id);
    }

    public function broadcastAs()
    {
    return 'test';
    }

    public function broadcastWith()
    {
    return ['msg' => ""];
    }
}
