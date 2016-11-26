<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contest extends Model
{
    protected $table = "contest_info";
    protected $primaryKey = "contest_id";

    /*
     * @function getState
     * @input $this
     *
     * @return int
     * @description get the state of the contest
     *              1 for running 0 for pending ,-1
     *              for ended
     */
    public function getState()
    {
        $curTime = time();
        if($this->contest_type != 2 && $curTime < strtotime($this->begin_time) )
            return env("CONTEST_PENDING", 0);
        if($this->contest_type == 2 && $curTime >= strtotime($this->register_begin_time) && $curTime < strtotime($this->register_end_time))
            return env("CONTEST_IN_REGISTER", 2);
        if($this->contest_type == 2 && $curTime < strtotime($this->register_begin_time) )
            return env("CONTEST_NOT_IN_REGISTER", 3);
        if($this->contest_type == 2 && $curTime < strtotime($this->begin_time) )
            return env("CONTEST_PENDING", 0);
        if($curTime >= strtotime($this->begin_time) && $curTime <= strtotime($this->end_time))
            return env("CONTEST_RUNNING", 1);
        return env("CONTEST_ENDED", -1);
    }

    /*
     * @function isRunning
     * @input $this
     *
     * @return bool
     * @description judge if the contest is running
     */
    public function isRunning()
    {
        return $this->getState() == env("CONTEST_RUNNING", 1);
    }

    /*
     * @function isPending
     * @input $this
     *
     * @return bool
     * @description judge if the contest is pending
     */
    public function isPending()
    {
        return $this->getState() == env("CONTEST_PENDING", 0);
    }

    /*
     * @function isEnded
     * @input $this
     *
     * @return bool
     * @description judge if the contest ends
     */
    public function isEnded()
    {
        return $this->getState() == env("CONTEST_ENDED", -1);
    }

    /*
     * @function isInRegister
     * @input $this
     *
     * @return bool
     * @description judge if the contest is in register
     */
    public function isInRegister()
    {
        return $this->getState() == env("CONTEST_IN_REGISTER", 2);
    }

    /*
     * @function isNotInRegister
     * @input $this
     *
     * @return bool
     * @description judge if the contest is not in register
     */
    public function isNotInRegister()
    {
        return $this->getState() == env("CONTEST_NOT_IN_REGISTER", 3);
    }

    /*
     * @function getContestItemsInPage
     * @input $itemsPerPage $page_id
     *
     * @return array
     * @description each time call this function, return
     *              an array that contain all the data needed
     *              for the pager
     */
    public static function getContestItemsInPage($itemsPerPage, $page_id)
    {
        $data = [];
        $contestObj = Contest::orderby('contest_id', 'desc')->skip(($page_id - 1) * $itemsPerPage)->take($itemsPerPage)->get();
        $contestNum = Contest::orderby('contest_id', 'desc')->count();

        for($count = 0; $count < $contestObj->count();$count++)
        {
            $data["contests"][$count] = $contestObj[$count];
            if($contestObj[$count]->isPending())
            {
                $data["contests"][$count]->status = "Pending";
            }
            else if($contestObj[$count]->isEnded())
            {
                $data["contests"][$count]->status = "Ended";
            }
            else if($contestObj[$count]->isRunning())
            {
                $data["contests"][$count]->status = "Running";
            }
            else if($contestObj[$count]->isInRegister())
            {
                $data["contests"][$count]->status = "Registering";
            }
            else if($contestObj[$count]->isNotInRegister())
            {
                $data["contests"][$count]->status = "Register Pending";
            }
        }
        if(($page_id - 1) * $itemsPerPage >= $contestNum)
        {
            $data["last_page"] = 1;
        }
        if($page_id == 1)
        {
            $data["first_page"] = 1;
        }
        $data["page_id"] = $page_id;
        $data["page_num"] = (int)($contestNum / $itemsPerPage + ($contestNum % $itemsPerPage == 0 ? 0 : 1));
        return $data;
    }

    /*
     * @function getFirstacList
     * @input $this
     *
     * @return $firstac
     * @description find the first ac submission of every problem of the contest
     */
    public function getFirstacList()
    {
        $contestProblemObj = ContestProblem::where('contest_id', $this->contest_id)->get();
        $firstac = [];
        foreach($contestProblemObj as $problem)
        {
            $submissionObj = Submission::select('uid')->where([
                'cid' => $this->contest_id,
                'pid' => $problem->problem_id,
                'result' => 'Accepted'
            ])->orderby('runid','asc')->limit(1)->first();
            if(isset($submissionObj))
                $firstac[$problem->problem_id] = $submissionObj->uid;
            else
                $firstac[$problem->problem_id] = -1;
        }
        return $firstac;
    }
}
