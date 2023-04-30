<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = ['type', 'receiver', 'data', 'readed'];
    protected $connection = 'mongodb';
    protected $table = 'c_notifications';

}
