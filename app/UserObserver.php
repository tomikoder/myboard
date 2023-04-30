<?php
 
namespace App;
 
use App\User;
use App\UserAD;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;


class UserObserver
{
    /**
     * Listen to the User created event.
     *
     * @param  User  $user
     * @return void
     */
    public function created(User $user)
    {
        $ad = UserAD::create(['user_id' => $user->id, 'code' => Uuid::uuid4()->toString(), 'expired_time' => Carbon::now()->addDays(5)]);
    }
 
    /**
     * Listen to the User deleting event.
     *
     * @param  User  $user
     * @return void
     */
}