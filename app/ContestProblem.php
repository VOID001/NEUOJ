<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContestProblem extends Model
{
    protected $table = "contest_problems";

    public function problem() {
        return $this->belongsTo("App\Problem", 'problem_id');
    }
}


