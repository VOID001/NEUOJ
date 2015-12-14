<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Userinfo extends Model
{
    protected $table = "userinfo";

    protected $fillable = ['school', 'nickname', 'stu_id', 'uid'];
}
