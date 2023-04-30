<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Post extends Model
{
    
    protected $fillable = ['title', 'text'];
    protected $casts = ['likes' => 'array'];

    public function user() 
    {
        return $this->belongsTo(User::class);
    }

    public function comments() 
    {
        return $this->hasMany(Comment::class);
    }

    protected  static  function  boot()
    {
        parent::boot();

        static::creating(function  ($model)  {
            $model->link = Uuid::uuid4()->toString();
            $model->likes = json_encode(array('users_id' => array()));
        });
    }
}
