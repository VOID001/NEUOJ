<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

use Redis;
use App\Submission;
use App\Userinfo;
use App\Contest;
use App\ContestProblem;
use App\ContestRanklist;

class updateContestRanklist extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $uid;
    protected $contest_id;
    protected $force;       // force for update ranklist without waiting
    protected $init;        // for initialize only

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($contest_id, $uid, $force, $init=false)
    {
        $this->uid = $uid;
        $this->contest_id = $contest_id;
        $this->force = $force;
        $this->init = $init;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $submissionObj = Submission::where([
            'cid' => $this->contest_id,
            'uid' => $this->uid
            ])->orderby('runid', 'asc')->get();
        if($submissionObj != NULL && $this->uid != 0)
        {
            $penalty_list = [];
            $result_list = [];
            $total_penalty = 0;
            $ac_count = 0;
            $contestObj = Contest::where('contest_id', $this->contest_id)->first();
            $contestProblemObj = ContestProblem::where('contest_id', $this->contest_id)->get();
            // get penalty for each problem
            foreach($contestProblemObj as $contestProblem)
            {
                $cpid = $contestProblem->contest_problem_id;
                $fb_submission = Submission::select('uid')->where(['cid' => $this->contest_id, 'pid' => $contestProblem->problem_id, 'result' => 'Accepted'])->orderby('runid', 'asc')->first();
                if(isset($fb_submission) && count($fb_submission) == 1)
                    $fb_uid = $fb_submission->uid;
                else
                    $fb_uid = 0;
                $problemSubmissionObj = $submissionObj->where('pid', $contestProblem->problem_id);
                $penalty_count = 0;
                if($problemSubmissionObj != NULL && count($problemSubmissionObj) > 0)
                {
                    foreach($problemSubmissionObj as $problemSubmission)
                    {
                        if($problemSubmission->result == 'Accepted')
                        {
                            $problem_time = strtotime($problemSubmission->submit_time) - strtotime($contestObj->begin_time);
                            $penalty_list[$cpid]['time'] = $this->time_to_str($problem_time);
                            $penalty_list[$cpid]['penalty'] = $penalty_count;
                            $total_penalty += $problem_time + $penalty_count * 20 * 60;
                            $result_list[$cpid] = "Accepted";
                            if($this->uid == $fb_uid || $fb_uid == 0)
                            {
                                $result_list[$cpid] = "First Blood";
                            }
                            $ac_count++;
                            break;
                        }
                        else
                        {
                            $penalty_count++;
                            $penalty_list[$cpid]['penalty'] = $penalty_count;
                            $result_list[$cpid] = $problemSubmission->result;
                        }
                    }
                }
            }
            $total_penalty = $this->time_to_str($total_penalty);
            //save to database(or redis)
            $contestRanklistObj = ContestRanklist::where(['contest_id' => $this->contest_id, 'uid' => $this->uid])->first();
            if($contestRanklistObj == NULL || count($contestRanklistObj) == 0)
                $contestRanklistObj = new ContestRanklist;
            $contestRanklistObj->contest_id = $this->contest_id;
            $contestRanklistObj->uid = $this->uid;
            $contestRanklistObj->penalty_list = json_encode($penalty_list);
            $contestRanklistObj->total_penalty = $total_penalty;
            $contestRanklistObj->total_ac = $ac_count;
            $contestRanklistObj->result_list = json_encode($result_list);
            $contestRanklistObj->save();
        }
        //when initializing, update only at the last dispatch
        if($this->init)
            return;
        //update ranklist every second
        $last_update_time = Redis::get('last_update_time_'.$this->contest_id);
        if($last_update_time == NULL)
        {
            Redis::set('last_update_time_'.$this->contest_id, time());
        }
        elseif(time()- $last_update_time < 5 && $this->force == false)
            return;
        //update ranklist
        $contestRanklistObj = ContestRanklist::where('contest_id', $this->contest_id)->get();
        $ranklist = $contestRanklistObj->all();
        usort($ranklist, ['self', 'cmp']);
        $rank = 1;
        foreach($ranklist as $user)
        {
            ContestRanklist::where(['contest_id' => $this->contest_id, 'uid' => $user->uid])->update(['rank' => $rank]);
            $rank++;
        }
        Redis::set('last_update_time_'.$this->contest_id, time());
    }

    public function time_to_str($time)
    {
        $hour = intval($time / 3600);
        $minute = intval($time % 3600 / 60);
        $second = intval($time % 60);
        $time = sprintf("%02d : %02d : %02d", $hour, $minute, $second);
        return $time;
    }

    public function str_to_time($time)
    {
        $hour = intval(strtok($time, ":"));
        $minute = intval(strtok(":"));
        $time = intval(strtok(":"));
        return $hour * 3600 + $minute * 60 + $time;
    }

    public function cmp($userA, $userB)
    {
        if($userA->total_ac == $userB->total_ac)
        {
            return $this->str_to_time($userA->total_penalty) > $this->str_to_time($userB->total_penalty);
        }
        return $userA->total_ac < $userB->total_ac;
    }
}
