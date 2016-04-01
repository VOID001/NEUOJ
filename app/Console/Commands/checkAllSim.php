<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\RESTController;
use App\Submission;
use Carbon\Carbon;

class checkAllSim extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sim:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $start_time = Carbon::now();
        $this->warn("Be sure to run this command under public/ folder, or sim will not work correctly");
        $this->info("Checking all codes in database, This maybe take sometime, Please wait");
        $allSubmissionObj = Submission::all();
        $restController = new RESTController();
        $count = 0;
        foreach($allSubmissionObj as $submissionObj)
        {
            $count++;
            /* for every 50 submissions told me the progress */
            if($count % 50 == 0)
            {
                $this->info("Check SIM for $count Submissions Done");
            }
            $restController->checkSIM($submissionObj->runid);
        }
        $end_time = Carbon::now();
        $time_used = $end_time->diffForHumans($start_time);
        $this->info("Done in $time_used");
    }
}
