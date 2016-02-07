<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Problem extends Model
{
    protected $table = "problems";

    protected $fillable = ["title" ,"description" , "visibility_locks" , "time_limit" , "mem_limit" , "output_limit" , "difficulty" , "author_id"];

    /*
     * @function getProblemTitle
     * @input $problem_id
     *
     * @return string
     * @description return the problem title by given problem_id
     *              or return "Deleted" when cannot find the problem
     */
    public static function getProblemTitle($problem_id)
    {
        $tmpRes = Problem::where('problem_id', $problem_id)->first();
        if($tmpRes == NULL)
            return -1;
        else
            return $tmpRes->title;
    }

    /*
     * @function problemExists
     * @input $problem_id
     *
     * @return bool
     * @description return whether the problem specified by $problem_id Exists
     */
    public static function problemExists($problem_id)
    {
        $tmpRes = Problem::where('problem_id', $problem_id)->first();
        if($tmpRes == NULL)
            return false;
        else
            return true;
    }
}
