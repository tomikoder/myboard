<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class MsgIn extends Model
{
    protected $fillable = ['receiver','readed', 'body', 'response', 'sender'];
    protected $connection = 'mongodb';
    protected $table = 'mongo_msgs_in';
    
    protected  static  function  boot()
    {
        parent::boot();

        static::creating(function  ($model)  {
            $model->link = Uuid::uuid4()->toString();
        });
    }

}
