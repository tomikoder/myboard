<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['parent_title', 'text', 'par_comm'];

    protected $table = 'comments';

    public function user() 
    {
        return $this->belongsTo(User::class);
    }

}
