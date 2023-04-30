<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Post;
use App\Policies\PolicyForPost;
use App\Comment;
use App\Policies\PolicForComment;
use App\Policies\PolicyForMessage;
use App\MsgIn;
use App\Policies\MsgPolicy;
use App\Policies\IsSuperUser;



class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
         Post::class => PolicyForPost::class,
         Comment::class => PolicForComment::class,
         Message::class => PolicyForMessage::class,
         MsgIn::class => MsgPolicy::class,
         User::class => IsSuperUser::class 
        ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
