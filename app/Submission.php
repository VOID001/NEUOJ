<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Request;

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

    /*
     * @function getAcCountByUserID
     * @input $uid
     *
     * @return $data
     * @description get the Accepted Submission all in the past days
     */
    public static function getAcCountByUserID($uid)
    {
        $dayNumObj = Submission::orderby('runid', 'asc')->first();
        if ($dayNumObj->count()) {
            $dayOld = strtotime($dayNumObj->created_at->format('Ymd'));
            $nowDate = strtotime(date('Ymd', time()));
            $dayNum = round(($nowDate - $dayOld) / 3600 / 24) + 1;
            $data = [];
            for ($i = 0; $i < $dayNum; $i++) {
                $data[$i]['date'] = date("Y-m-d", strtotime('-' . $i . ' day'));
                $data[$i]['count'] = 0;
            }
            $submissionObj = Submission::select('result', 'created_at')->orderby('runid', 'asc')->where('uid', $uid)->get();
            $submissionNum = $submissionObj->count();
            for ($i = 0; $i < $submissionNum; $i++) {
                if ($submissionObj[$i]['result'] == 'Accepted') {
                    $submissionDate = strtotime($submissionObj[$i]->created_at->format('Ymd'));
                    $diffDays = round(($nowDate - $submissionDate) / 3600 / 24);
                    if ($diffDays < $dayNum)
                        $data[$diffDays]['count']++;
                }
            }
            return $data;
        } else
            return null;
    }
}
