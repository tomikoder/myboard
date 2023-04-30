<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;
use Ramsey\Uuid\Uuid;


class MsgOut extends Model
{
    protected $fillable = ['receiver', 'data', 'readed'];
    protected $connection = 'mongodb';
    protected $table = 'mongo_msgs_out';

    protected  static  function  boot()
    {
        parent::boot();

        static::creating(function  ($model)  {
            $model->link = Uuid::uuid4()->toString();
        });
    }

}
