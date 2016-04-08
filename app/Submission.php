<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $table = "submissions";

    protected $primaryKey = "runid";

    protected $fillable = [""];

    public function sim()
    {
        return $this->hasOne('App\Sim', 'runid');
    }

    /*
     * @function getValidSubmissionCount
     * @input $contest_id, $problem_id
     *
     * @return $totalSubmissionCount
     * @description count total submission before firstAc, including the first ac
     */
    public static function getValidSubmissionCount($contest_id, $problem_id)
    {
		if($contest_id == 0)
			$totalSubmission = Submission::where([
	            'pid' => $problem_id,
	        ])->orderby('uid', 'asc')->get();
	    else
			$totalSubmission = Submission::where([
            'pid' => $problem_id,
            'cid' => $contest_id,
			])->orderby('uid', 'asc')->get();
        $tmpuid = 0;
        $tmpac = 0;
        $totalSubmissionCount = 0;
        foreach($totalSubmission as $submission)
        {
            if($tmpuid != $submission->uid)
            {
                $tmpuid = $submission->uid;
                $tmpac = 0;
            }
            if(!$tmpac)
                $totalSubmissionCount++;
            if($submission->result == 'Accepted')
                $tmpac = 1;
        }
        return $totalSubmissionCount;
    }
}
