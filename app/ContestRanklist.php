<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContestRanklist extends Model
{
    protected $table = "contest_ranklist";


    public function user() {
        return $this->belongsTo('App\User', 'uid');
    }
}
