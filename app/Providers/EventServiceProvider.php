<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\PostWasAdded' => [
            'App\Listeners\Increment_Post_Num',
        ],
        'App\Events\PostWasRemoved' => [
            'App\Listeners\Decrement_Post_Num',
        ],
        'App\Events\PostWasLiked' => [
            'App\Listeners\IncreaseNumOfLikes',
        ],
        'App\Events\NotificationWasRead' => [
            'App\Listeners\MarkAsRead',
        ],
        'App\Events\PostWasComment2' => [
            'App\Listeners\NotfifyPostUser2',
        ],
        'App\Events\CommentWasComment' => [
            'App\Listeners\NotfifyCommentUser',],
        'App\Events\MessageWasSent' => [
            'App\Listeners\NotfifyMessageReceiver'
        ],
        'App\Events\PrivateMessageWasSent' => [
            'App\Listeners\NotfifyPrivateMessageReceiver'
        ],
        'App\Events\DateUpdate' => [
            'App\Listeners\UpdateUserDate'

        ],


    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
