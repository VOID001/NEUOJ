<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Submission;
use App\Userinfo;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;


class updateUserProblemCount extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $uid;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($uid)
    {
        $this->uid = $uid;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $submission_num = Submission::select("uid")->where("uid", $this->uid)->get()->count();
        $ac_submission_num = Submission::select("uid", "pid")->where(["uid" => $this->uid, "result" => "accepted"])->get()->unique('pid')->count();
        Userinfo::where("uid", $this->uid)->update(["submit_count" => $submission_num, "ac_count" => $ac_submission_num]);
    }
}
