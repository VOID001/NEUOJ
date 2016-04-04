<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrainProblem extends Model
{
    protected $table = "train_problem";

    /*
     *@function problem
     *@input
     *
     *@return relation
     *@description get problem record associated with train problem
     */
    public function problem()
    {
         return $this->hasOne('App\Problem', 'problem_id', 'problem_id');
    }
}
