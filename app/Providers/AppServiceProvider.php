<?php

namespace App\Providers;
use App\Post;
use App\Comment;
use App\User;
use App\UserObserver;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    public function boot()
    {
        User::observe(UserObserver::class);
    }
}
