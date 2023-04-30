<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\User;
use App\Post;
use App\Comment;

class PostWasComment2 implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;
    public $user;
    public $post;
    public $comment;


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Post $post, Comment $comment, User $user)
    {
        $this->user = $user;
        $this->post = $post;
        $this->comment = $comment;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-'.$this->post->user_id);
    }

    public function broadcastAs()
    {
    return 'test';
    }

    public function broadcastWith()
    {
    return ['msg' => "OK"];
    }


}
