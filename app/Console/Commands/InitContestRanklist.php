<?php

namespace App\Console\Commands;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Console\Command;
use App\Jobs\updateContestRanklist;
use App\User;
use App\Contest;
use App\ContestUser;
use App\Submission;

class InitContestRanklist extends Command
{
    use DispatchesJobs;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contest_ranklist:init {cid=all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize contest ranklist with new method';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $cid = $this->argument('cid');
        if($cid == "all")
        {
            $contestObj = Contest::get();
        }
        else
        {
            $contestObj = Contest::where('contest_id', $cid)->get();
        }
        if($contestObj == NULL)
        {
            $this->error('Contest not exists!');
            return;
        }
        foreach($contestObj as $contest)
        {
            $this->info('Contest ' . $contest->contest_id . " ranklist initializing...");
            $contestUserObj = ContestUser::select("username")->where('contest_id', $contest->contest_id)->get();
            $i = 0;
            $total = $contestUserObj->count();
            if($total == 0)
            {
                $contestUserObj = Submission::select('uid')->where(['cid' => $contest->contest_id])->get()->unique('uid');
                $total = count($contestUserObj);
            }
            foreach($contestUserObj as $contestUser)
            {
                if($i % 100 == 0)
                    $this->info("($i/$total)");
                $i++;
                $uid = 0;
                if(!isset($contestUser->uid))
                {
                    $userObj = User::where('username', $contestUser->username)->first();
                    if($userObj == NULL)
                    {
                        if($i == $total)
                            $this->dispatch(new updateContestRanklist($contest->contest_id, 0, true));
                        continue;
                    }
                    $uid = $userObj->uid;
                }
                else
                    $uid = $contestUser->uid;
                if($i == $total)
                    $this->dispatch(new updateContestRanklist($contest->contest_id, $uid, true));
                else
                    $this->dispatch(new updateContestRanklist($contest->contest_id, $uid, false, true));
            }
            $this->info("($total/$total)");
            $this->info("Contest " . $contest->contest_id . " initialization finished");
        }
        $this->info("Initialization Finished");
    }
}
