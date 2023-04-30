<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Broadcast;
use App\User;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Broadcast::routes();

        /*
         * Authenticate the user's personal channel...
         */
        Broadcast::channel('channel-*', function ($user, $userId) {
            return (int) $user->id === (int) $userId;
        });
        Broadcast::channel('online', function (User $user) {
            if ($user) {
                return ['id' => $user->id,
                        'name' => $user->name,
                        ];
            }
        });
        Broadcast::channel('chat.*', function (User $user, string $code) {
            if ($user->canjoin($code)) {
                return ['id' => $user->id,
                        'name' => $user->name,
                        ];
            }
        });
    }
}
