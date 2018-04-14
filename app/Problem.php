<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\RoleController;
use App\Submission;
use Request;

class Problem extends Model
{
    protected $table = "problems";

    protected $primaryKey = "problem_id";

    protected $fillable = ["title" ,"description" , "visibility_locks" , "time_limit" , "mem_limit" , "output_limit" , "difficulty" , "author_id"];

    public function author() {
        return $this->belongsTo('App\User', 'author_id');
    }

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

    /*
     * @function getProblemItemsInPage
     * @input $itemsPerPage, $page_id
     *
     * @return Array
     * @description fetch Problems in page, if page_id is invalid
     *              page_id set to a valid number
     */
    public static function getProblemItemsInPage($itemsPerPage, $page_id)
    {
        $data = [];
        $data["problems"] = [];
        if(RoleController::is('admin'))
            $problemNum = Problem::orderby('problem_id', 'asc')->count();
        else
            $problemNum = Problem::orderby('problem_id', 'asc')
                ->where('visibility_locks', 0)->count();

        /* If page_id > page_num then page_id set to the last page available */
        $data["page_id"] = $page_id;
        $data["page_num"] = (int)($problemNum / $itemsPerPage + ($problemNum % $itemsPerPage == 0 ? 0 : 1));
        if($page_id > $data["page_num"])
            $page_id = $data["page_num"];
        if($page_id <= 0)
            $page_id = 1;

        if (RoleController::is('admin'))
            $problemObj = Problem::with('author')
                ->orderby('problem_id', 'asc')
                ->skip(($page_id - 1) * $itemsPerPage)->take($itemsPerPage)->get();
        else
            $problemObj = Problem::with('author')
                ->orderby('problem_id', 'asc')
                ->where('visibility_locks', 0)
                ->skip(($page_id - 1) * $itemsPerPage)
                ->take($itemsPerPage)
                ->get();
        $problemIDs = $problemObj->pluck('problem_id')->toArray();
        $submissionBuilder = Submission::select('pid')
            ->where('uid', Request::session()->get('uid'))
            ->whereIn('pid', $problemIDs);
        $submissions = $submissionBuilder->get();
        $ACSubmissions = $submissionBuilder->where('result', 'Accepted')->get();

        for ($i = 0; $i < $problemObj->count(); $i++)
        {
            $pid = $problemObj[$i]->pid;
            $data["problems"][$i] = $problemObj[$i];
            $data['problems'][$i]->submission_count = Submission::getValidSubmissionCount(0, $problemObj[$i]->problem_id);
            $data['problems'][$i]->ac_count = Submission::where('pid', $problemObj[$i]->problem_id)
                ->where('result', 'Accepted')->get()->unique('uid')->count();
            $data['problems'][$i]->author = $problemObj[$i]->author->username;
//            $data['problems'][$i]->used_times = $problemObj[$i]->getNumberOfUsedContests();
            if(Request::session()->has('uid'))
            {
                if ($ACSubmissions->search(function($item, $key) use ($pid) {
                    return $item->pid == $pid;
                })) {
                    $data['problems'][$i]->status = "Y";
                } else if ($submissions->search(function($item, $key) use ($pid) {
                    return $item->pid == $pid;
                })) {
                    $data['problems'][$i]->status = "N";
                } else
                    $data['problems'][$i]->status = "T";
            }
            else
            {
                $data['problems'][$i]->status = "T";
            }
        }
        if(($page_id - 1) * $itemsPerPage >= $problemNum)
        {
            $data["lastPage"] = 1;
        }
        if($page_id == 1)
        {
            $data["firstPage"] = 1;
        }
        return $data;
    }

    /*
     * @function isUsedByContest
     * @input $this
     *
     * @return bool
     * @description tell whether the problem is used by a contest or not
     */
    public function isUsedByContest()
    {
        $contestProblemList = ContestProblem::where('problem_id', $this->problem_id)->get();
        if(isset($contestProblemList))
            return 1;
        else return 0;
    }

    /*
     * @function getNumberOfUsedContests
     * @input $this
     *
     * @return $i
     * @description tell how many contests the problem is used by
     */
    public function  getNumberOfUsedContests()
    {
        $contestProblemList = ContestProblem::select('contest_id')->where('problem_id', $this->problem_id)->get();
        $i = 0;
        if($this->isUsedByContest())
        {
            foreach($contestProblemList as $contestProblem)
            {
                $contestObj = Contest::select('contest_id')->where('contest_id', $contestProblem->contest_id)->first();
                if($contestObj != NULL && !$contestObj->isEnded())
                    $i++;
            }
        }
        return $i;
    }
}
