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
        if($curTime < strtotime($this->begin_time))
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
}
