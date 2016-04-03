<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function info()
    {
        return $this->hasOne('App\Userinfo', 'uid', 'author_id');
    }
}
