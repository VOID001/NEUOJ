<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\RoleController;
use App\Submission;

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
            $problemObj = Problem::orderby('problem_id', 'asc')->get();
        else
            $problemObj = Problem::orderby('problem_id', 'asc')->where('visibility_locks', 0)->get();

        $problemNum = $problemObj->count();

        /* If page_id > page_num then page_id set to the last page available */
        $data["page_id"] = $page_id;
        $data["page_num"] = (int)($problemNum / $itemsPerPage + ($problemNum % $itemsPerPage == 0 ? 0 : 1));
        if($page_id > $data["page_num"])
            $page_id = $data["page_num"];
        if($page_id <= 0)
            $page_id = 1;

        for($count = 0, $i = ($page_id - 1) * $itemsPerPage; $i < $problemNum && $count < $itemsPerPage; $i++, $count++)
        {
            $data["problems"][$count] = $problemObj[$i];
            $data['problems'][$count]->submission_count = Submission::where('pid', $problemObj[$i]->problem_id)->count();
            $data['problems'][$count]->ac_count = Submission::where('pid', $problemObj[$i]->problem_id)
                ->where('result', 'Accepted')->count();
            $authorObj = User::where('uid', $problemObj[$i]->author_id)->first();
            $data['problems'][$count]->author = $authorObj["username"];
            $data['problems'][$count]->used_times = $problemObj[$i]->getNumberOfUsedContests();
        }
        if($i >= $problemNum)
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
        $contestProblemList = ContestProblem::where('problem_id', $this->problem_id)->get();
        $i = 0;
        if($this->isUsedByContest())
        {
            foreach($contestProblemList as $contestProblem)
            {
                $contestObj = Contest::where('contest_id', $contestProblem->contest_id)->first();
                if(!$contestObj->isEnded())
                    $i++;
            }
        }
        return $i;
    }
}
