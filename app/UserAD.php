<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAD extends Model
{
    protected $table = 'users_ad';
    protected $fillable = [
        'expired_time', 'code', 'user_id'
    ];

}
