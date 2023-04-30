<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Post;
use App\User;
use App\Comment;

class PostWasComment extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
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
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'post_id' => $this->post->id,
            'post_link' => $this->post->link,
            'user_name' => $this->user->name,
            'comment_id' => $this->comment->id,
            'date' => $this->comment->created_at,
            'comment_text' => $this->comment->text,
        ];
    }

    public function toBroadcast($notifiable)
    {
        return ["msg" => "OK"];    
    }
}
