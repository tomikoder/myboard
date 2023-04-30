<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\MsgIn;
use App\UserAD;


class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function comments() 
    {
        return $this->hasMany(Comment::class);
    }

    public function additionaldata() {
        return $this->hasOne(UserAD::class);
    }

    public function canjoin(string $link) {
        $msg = MsgIn::where('link', '=', $link)->first();
        if ($msg && ($this->id == $msg->receiver || ($this->id == $msg->sender))) {
            return TRUE;
        }
    }
}
