<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
}
