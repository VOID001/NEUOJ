<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Submission;
use App\Contest;
use App\ContestProblem;
use App\Problem;
use App\User;
use Storage;
use Carbon\Carbon;

class StartPresureTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:start
                            {--num=5 : Submission per second}
                            {--total=100 : Total test num}
                            {--uid=3363 : Test Uid}
                            {--problem=16}
                            {--wait=30: Wait time after all submissions finished}
                            {--time=10: Time that a judge should be finished in}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start OJ Presure Test';

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
        $this->info("NEUOJ Presure Test Starts");
        $num = $this->option('num');
        $total = $this->option('total');
        $uid = $this->option('uid');
        $wait_time = $this->option('wait');
        $error_time = $this->option('time');
        if(User::where('uid', $uid)->first() == NULL)
        {
            $this->error('user '.$uid.' not exists!!!');
            return;
        }
        $problem_id = $this->option('problem');
        if(Problem::where('problem_id', $problem_id)->first() == NULL)
        {
            $this->error('problem '.$problem_id.' not exitst!!!');
            return;
        }
        $test_files = [];
        if(Storage::has('/presure_test/ac.cpp'))
            $test_files[0]['code'] = Storage::get('/presure_test/ac.cpp');
        if(Storage::has('/presure_test/wa.cpp'))
            $test_files[1]['code'] = Storage::get('/presure_test/wa.cpp');
        if(Storage::has('/presure_test/tle.cpp'))
            $test_files[2]['code'] = Storage::get('/presure_test/tle.cpp');
        if(Storage::has('/presure_test/re.cpp'))
            $test_files[3]['code'] = Storage::get('/presure_test/re.cpp');
        if(Storage::has('/presure_test/ole.cpp'))
            $test_files[4]['code'] = Storage::get('/presure_test/ole.cpp');
        if(Storage::has('/presure_test/mle.cpp'))
            $test_files[5]['code'] = Storage::get('/presure_test/mle.cpp');
        if(Storage::has('/presure_test/ce.cpp'))
            $test_files[6]['code'] = Storage::get('/presure_test/ce.cpp');
        $test_files[0]['status'] = 'Accepted';
        $test_files[1]['status'] = 'Wrong Answer';
        $test_files[2]['status'] = 'Time Limit Exceed';
        $test_files[3]['status'] = 'Runtime Error';
        $test_files[4]['status'] = 'Output Limit Exceed';
        $test_files[5]['status'] = 'Memory Limit Exceed';
        $test_files[6]['status'] = 'Compile Error';
        $counter = 0;
        $result = [];
        for($i = 0; $i < $total; $i++)
        {
            $rand = rand(0,6);
            $submissionObj = new Submission;
            $fileName = $uid."-".$problem_id."-".time().".cpp";
            Storage::put("submissions/".$fileName, $test_files[$rand]['code']);
            $submissionObj->pid = $problem_id;
            $submissionObj->uid = $uid;
            $submissionObj->cid = 0;
            $submissionObj->lang = 'C++';
            $submissionObj->result = "Pending";
            $submissionObj->submit_time = date('Y-m-d-H:i:s');
            $submissionObj->submit_file = $fileName;
            $submissionObj->md5sum = md5($test_files[$rand]['code']);
            $submissionObj->judge_status = 0;
            $submissionObj->save();
            $this->info($i.": submitted succeed with status ".$test_files[$rand]['status'].' with runid '.$submissionObj->runid);
            $result[$i]['runid'] = $submissionObj->runid;
            $result[$i]['status'] = $test_files[$rand]['status'];
            $result[$i]['result'] = ' ';
            $result[$i]['start_time'] = Carbon::now();
            $result[$i]['wait_time'] = 0;
            $result[$i]['error'] = 0;  //if result doesn't match and result isn't pending, error=1
            $counter++;
            if($counter == $num)
            {
                $this->info('wait...');
                $this->checkPreviousResult($result);
                sleep(1);
                $counter = 0;
            }
        }
        $this->info("Wait for ".$wait_time." seconds");
        for($i = 0; $i < $wait_time; $i++)
        {
            $this->checkPreviousResult($result);
            sleep(1);
        }
        $right = 0;
        $long = 0;
        $longlist = [];
        $error = 0;
        $errlist = [];
        $pending = 0;
        $pendinglist = [];
        for($i = 0; $i < count($result); $i++)
        {
            if($result[$i]['status'] == $result[$i]['result'])
            {
                $right++;
                if($result[$i]['wait_time'] > $error_time)
                {
                    $longlist[$long] = $result[$i];
                    $long++;
                }
            }
            else if($result[$i]['result'] == "Pending")
            {
                $pendinglist[$pending] = $result[$i];
                $pending++;
            }
            else
            {
                $errlist[$error] = $result[$i];
                $error++;
            }
        }
        $msg = "NEUOJ Presure test\nTime: ".Carbon::now()."\n";
        $msg .= "Result:\n";
        $msg .= $right." submissions's judge finished with correct judgement with ".$long." submissions waiting too long.\n";
        $msg .= $pending. " submissions's judge are still pending.\n";
        $msg .= $error. " submissions's judge finished with wrong judgement.\n";
        $msg .= "\nSubmission list with too lang wait time:$long\n";
        for($i = 0; $i < $long; $i++)
        {
            $msg .= "Runid: ".$longlist[$i]['runid']." code_status: ".$longlist[$i]['status']." code_result: ".$longlist[$i]['result']." code_wait_time: ".$longlist[$i]['wait_time']."\n";
        }
        $msg .= "\nSubmission list with pending status:$pending\n";
        for($i = 0; $i < $pending; $i++)
        {
            $msg .= "Runid: ".$pendinglist[$i]['runid']." code_status: ".$pendinglist[$i]['status']." code_result: ".$pendinglist[$i]['result']." code_wait_time: ".$pendinglist[$i]['wait_time']."\n";
        }
        $msg .= "\nSubmission list with wrong status:$error\n";
        for($i = 0; $i < $error; $i++)
        {
            $msg .= "Runid: ".$errlist[$i]['runid']." code_status: ".$errlist[$i]['status']." code_result: ".$errlist[$i]['result']." code_wait_time: ".$errlist[$i]['wait_time']."\n";
        }
        if(!Storage::has('/test_logs'))
        {
            Storage::makeDirectory('test_logs');
        }
        Storage::put("/test_logs/test_log_".date('Y-m-d-H-i-s').".txt", $msg);
        $this->info($right." submissions's judge finished with correct judgement with ".$long." submissions waiting too long.");
        $this->info($pending. " submissions's judge are still pending.");
        $this->info($error. " submissions's judge finished with wrong judgement.");
        $this->info("To see more info, please read the log in storage.");
        $this->info("Test ends.");
    }
    
    public function checkPreviousResult(&$result)
    {
        
        //Check previous result
        for($i = 0; $i < count($result); $i++)
        {
            if($result[$i]['status'] == $result[$i]['result'] || $result[$i]['error'] == 1)
                continue;
            $resultObj = Submission::where('runid', $result[$i]['runid'])->first();
            $result[$i]['result'] = $resultObj->result;
            if($result[$i]['result'] != $result[$i]['status'])
            {
                if($result[$i]['result'] == "Pending")
                {
                    $now = Carbon::now();
                    $result[$i]['wait_time'] = $now->diffInSeconds($result[$i]['start_time']);
                }
                else $result[$i]['error'] = 1;
            }
        }
    }
}
