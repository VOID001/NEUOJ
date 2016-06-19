<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Userinfo extends Model
{
    protected $table = "userinfo";

    protected $primaryKey = "info_id";

    protected $fillable = ['school', 'nickname', 'stu_id', 'uid'];
}
