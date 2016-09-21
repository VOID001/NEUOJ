<?php

namespace App\Http\Controllers;

use App\ContestBalloon;
use App\ContestBalloonEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Storage;
use App\Problem;
use App\User;
use App\Submission;
use App\Executable;
use App\Testcase;
use App\Contest;
use App\ContestProblem;
use App\Sim;
use App\Jobs\updateUserProblemCount;

class RESTController extends Controller
{
    public function getConfig(Request $request)
    {
        /*
         * Use fake json config , same as the default config of domjudge
         */
        $json = '{"clar_categories":{ "general":"General issue", "tech":"Technical issue" }, "script_timelimit":30, "script_memory_limit":2097152, "script_filesize_limit":65536, "memory_limit":524288, "output_limit":4096, "process_limit":64, "sourcesize_limit":256, "sourcefiles_limit":100, "timelimit_overshoot":"1s|10%", "verification_required":0, "show_affiliations":1, "show_pending":0, "show_compile":2, "show_sample_output":0, "show_balloons_postfreeze":1, "penalty_time":20, "compile_penalty":1, "results_prio":{ "memory-limit":"99", "output-limit":"99", "run-error":"99", "timelimit":"99", "wrong-answer":"30", "no-output":"10", "correct":"1" }, "results_remap":[ ], "lazy_eval_results":1, "enable_printing":0, "time_format":"%H:%M", "default_compare":"compare", "default_run":"run", "allow_registration":0, "judgehost_warning":30, "judgehost_critical":120, "thumbnail_size":128 }';
        return $json;
    }

    public function postJudgings(Request $request)
    {

        $input = $request->input();

        $langsufix = [
                "C" => "c",
                "Java" => "java",
                "C++11" => "cc",
                "C++" => "cpp",
            ];
        $jsonObj = [];
        $submission = Submission::where('judge_status', 0)->first();
        if($submission == NULL)
            return response()->json(NULL);

        /* This lock should put as soon as the function entered for multi-judgehost safe*/
        Submission::where('runid', $submission->runid)->update([
            "judge_status" => 1,
            /* Sometime domjudge call judgehost judgehost , sometime call it hostname  = = */
            "judgeid" => $input['judgehost']
        ]);

        $problem = Problem::where('problem_id', $submission->pid)->first();
        $runExecutable = Executable::where('execid', 'run')->first();
        $compileExecutable = Executable::where('execid', $langsufix[$submission->lang])->first();
        $compareExecutable = Executable::where('execid', 'compare')->first();
        /*
         * Make the responce format readable for judgehost
         */
        $jsonObj["submitid"] = $submission->runid;
        $jsonObj["cid"] = 0;
        $jsonObj["teamid"] = $submission->uid;
        $jsonObj["probid"] = $submission->pid;
        $jsonObj["langid"] = $langsufix[$submission->lang];
        $jsonObj["rejudgingid"] = "";
        $jsonObj["maxruntime"] = $problem->time_limit;
        $jsonObj["memlimit"] = $problem->mem_limit;
        $jsonObj["outputlimit"] = $problem->output_limit;
        $jsonObj["run"] = "run";
        $jsonObj["compare_md5sum"] = $compareExecutable->md5sum;
        $jsonObj["run_md5sum"] = $runExecutable->md5sum;
        $jsonObj["compile_script_md5sum"] = $compileExecutable->md5sum;
        $jsonObj["compare"] = "compare";
        $jsonObj["compare_args"] = "";
        $jsonObj["compile_script"] = $jsonObj["langid"];
        $jsonObj["judgingid"] = $jsonObj["submitid"];
        /*
         * Save current judging_run into database
         * This table only works for domjudge, let GET api/testcases?judgingid=id can get current testcase
         * Now only give one problem one testcase , future will support multitestcases
         */
        //$judgingRun = new JudgingRun;
        //$judgingRun->judgingid =

        return response()->json($jsonObj);
    }

    public function getSubmissionFiles(Request $request)
    {
        $input = $request->input();
        $submission = Submission::where('runid', $input['id'])->first();
        $jsonObj[0]["filename"] = $submission->submit_file;
        $content = Storage::get("submissions/".$submission->submit_file);
        $jsonObj[0]["content"] = base64_encode($content);

        return response()->json($jsonObj);
    }

    /**
     * @function postJudgeHosts
     * @input $request POST data
     *
     * @return JSON(null)
     * @description When A judgehost register itself to NEUOJ
     *              First give all the unfinished judge own by him back
     *              Then return json null to judgehost
     */
    public function postJudgeHosts(Request $request)
    {
        $input = $request->input();

        Submission::where([
            /* Sometime domjudge call judgehost judgehost , sometime call it hostname  = = */
            'judgeid' => $input['hostname'],
            'judge_status' => 1,

        ])->update(['judge_status' => 0]);
        return response()->json(NULL);
    }

    public function getExecutable(Request $request)
    {
        $content = Storage::get("executables/".$request->input('execid').".zip");
        $content = base64_encode($content);
        return response()->json($content);
    }

    public function putJudgings(Request $request, $id)
    {
        $input = $request->input();

        if($input["compile_success"] != "1")
        {
            Submission::where('runid', $id)->update([
                "judge_status" => 3,
                "err_info" => base64_decode($input['output_compile']),
                "result" => "Compile Error",
            ]);
        }
    }

    public function getTestcases(Request $request)
    {
        $jsonObj = [];
        $input = $request->input();
        $submission = Submission::where('runid', $input["judgingid"])->where('judge_status', 1)->first();
        if($submission == NULL)
            return response()->json(NULL);
        $testcase = Testcase::where('pid', $submission->pid)->first();
        $jsonObj["testcaseid"] = $testcase->testcase_id;
        $jsonObj["rank"] = 1; //Now only give one problem one testcase so rank is hard-coded
        $jsonObj["probid"] = $testcase->pid;
        $jsonObj["md5sum_input"] = $testcase->md5sum_input;
        $jsonObj["md5sum_output"] = $testcase->md5sum_output;

        return response()->json($jsonObj);
    }

    public function getTestcaseFiles(Request $request)
    {
        $jsonData = "";
        $input = $request->input();
        $testcase = Testcase::where("testcase_id", $input["testcaseid"])->first();
        if(isset($input["input"]))
        {
            $jsonData = Storage::get("testdata/".$testcase->input_file_name);
        }
        else if(isset($input["output"]))
        {
            $jsonData = Storage::get("testdata/".$testcase->output_file_name);
        }
        $jsonData = base64_encode($jsonData);
        return response()->json($jsonData);
    }

    public function postJudgingRuns(Request $request)
    {
        $resultMapping = [
            "wrong-answer" => "Wrong Answer",
            "correct" => "Accepted",
            "no-output" => "Wrong Answer",
            "compiler-error" => "Compile Error",
            "run-error" => "Runtime Error",
            "timelimit" => "Time Limit Exceed",
        ];
        $input = $request->input();
        $submissionObj = Submission::where('runid', $input['judgingid'])->first();
        var_dump($input);

        $output_system = $this->parseSystemMeta($input['output_system']);


        /*
         * Judge Whether the code is copied from another code
         * Judge this only when the result is AC
         */
        if($input["runresult"] == "correct")
            $this->checkSIM($input['judgingid']);

        /* Contest Only, Judge for First Blood */
        if($input["runresult"] == "correct" && $submissionObj->cid != 0)
        {
            $contestObj = Contest::where('contest_id', $submissionObj->cid)->first();
            $contestProblemObj = ContestProblem::where([
                "contest_id" => $contestObj->contest_id,
                "problem_id" => $submissionObj->pid,
            ])->first();
            if($contestProblemObj->first_ac == 0)
            {
                $first_ac = Submission::where('runid', $input['judgingid'])->first()->uid;
                ContestProblem::where([
                    "contest_id" => $contestObj->contest_id,
                    "problem_id" => $submissionObj->pid,
                ])->update(["first_ac" => $first_ac]);
            }
        }
        Submission::where('runid', $input["judgingid"])->update(
            [
                "result" => $resultMapping[$input["runresult"]],
                "exec_time" => $output_system["wall_time"],
                "exec_mem" => $output_system["memory_used"],
                "judge_status" => 3,
            ]
        );

        /* update Ranklist queue */
        $this->dispatch(new updateUserProblemCount($submissionObj->uid));

        //var_dump($input);
        /* Balloon */
        $submissionObj = Submission::where('runid', $input['judgingid'])->first();
        if($submissionObj->cid != 0)
        {
            /* Not accepted */
            if ($resultMapping[$input["runresult"]] != "Accepted")
            {
                $contestBalloon = ContestBalloon::all();
                foreach ($contestBalloon as $contestBalloonObj)
                {
                    //var_dump($contestBalloonObj->runid);
                    if($contestBalloonObj->runid == $submissionObj->runid)
                    {
                        if ($contestBalloonObj->balloon_status == 1) /* AC rejudging */
                        {
                            /* discard balloon */
                            $contestBalloonObj->delete();
                            /* push into event queue (discard balloon) */
                            $contestBalloonEventObj = new ContestBalloonEvent();
                            $contestBalloonEventObj->runid = $contestBalloonObj->runid;
                            $contestBalloonEventObj->event_status = env('BALLOON_DISCARD',2);
                            $contestBalloonEventObj->save();
                        }
                        break;
                    }
                }
            }
            /* Accepted */
            else
            {
                $contestBalloon = ContestBalloon::all();
                $balloonExists = 0;
                foreach ($contestBalloon as $contestBalloonObj)
                {
                    $contestBalloonSubmissionObj = Submission::where('runid', $contestBalloonObj->runid)->first();
                    if ($submissionObj->cid == $contestBalloonSubmissionObj->cid && $submissionObj->pid == $contestBalloonSubmissionObj->pid && $submissionObj->uid == $contestBalloonSubmissionObj->uid)
                    {
                        if ($contestBalloonObj->balloon_status == 1) /* AC rejudging */
                        {
                            /* Send balloon */
                            $contestBalloonObj->balloon_status == 0;
                            $contestBalloonObj->save();
                        }
                        $balloonExists = 1;
                        break;
                    }
                }
                if ($balloonExists == 0)
                {
                    $contestBalloonObj = new ContestBalloon();
                    $contestBalloonObj->runid = $submissionObj->runid;
                    $contestBalloonObj->balloon_status = 0; /* Send balloon */
                    $contestBalloonObj->save();
                    /* push into event queue (send balloon) */
                    $contestBalloonEventObj = new ContestBalloonEvent();
                    $contestBalloonEventObj->runid = $contestBalloonObj->runid;
                    $contestBalloonEventObj->event_status = env('BALLOON_SEND',1);
                    $contestBalloonEventObj->save();
                }
            }
        }
    }

    /**
     * @function parseSystemMeta
     * @input String
     *
     * @return String
     * @description Use to parse the system.out info
     *              get the wall time and mem limit
     */
    public function parseSystemMeta($meta)
    {
        $result = [];
        $result[0] = 0;
        $result[1] = 0;
        $result[2] = 0;
        $dot_temp = 0;

        $meta = base64_decode($meta);
        $len = strlen($meta);

        $flag = 0;
        $dot = false;
        $dot_count = 0;
        for($i = 0; $i < $len; $i++)
        {
            if(is_numeric($meta[$i]) || $meta[$i] == '.')
            {
                if($meta[$i] == '.')
                {
                    $dot = true;
                }
                else
                {
                    if($dot)
                    {
                        $dot_temp = $dot_temp * 10 + ($meta[$i] - '0');
                        $dot_count++;
                    }
                    else
                    {
                        $result[$flag] = $result[$flag] * 10 + ($meta[$i] - '0');
                    }
                }
            }
            /* First Element Parse OK */
            else if($meta[$i] == ',')
            {
                $flag = 1;
                $result[0] = $result[0] + ($dot_temp * 1.0) / pow(10, $dot_count);
                $dot = false;
                $dot_count = 0;
                $dot_temp = 0;
            }
            /* Second Element Parse OK */
            else if($meta[$i] == ':' && $flag == 1)
            {
                $flag = 2;
                $result[1] = $result[1] + ($dot_temp * 1.0) / pow(10, $dot_count);
                $dot = false;
                $dot_count = 0;
                $dot_temp = 0;
            }
        }
        /* Last Element Parse OK */

        $result["run_time"] = $result[0];
        $result["wall_time"] = $result[1];
        $result["memory_used"] = $result[2];

        return $result;

    }

    /**
     * @function CheckSIM
     * @input $run_id
     *
     * @return NULL
     * @description Given a run_id, Check If there is code similar to
     *              this submission(sim >= 80%) Store the similarity in DB
     *              if not, Just Add a Simdiff File to storage
     */
    public function checkSIM($run_id)
    {
        $simLangMapArr = [
            'C' => 'sim_c',
            'C++' => 'sim_c',
            /* Now only support two langs */
        ];
        $currentSubmissionObj = Submission::find($run_id);

        /* Only Accepted results are considered */
        $relatedSubmissionObj = Submission::where([
            'pid' => $currentSubmissionObj->pid,
            'lang' => $currentSubmissionObj->lang,
            'result' => "Accepted"
        ])->get();
        $lang = $currentSubmissionObj->lang;
        $max_similarity = 0;
        $max_similarity_runid = -1;

        $SIMDIR = env('SIMDIR', './sim');
        $SUBMISSIONSDIR = env('SUBMISSIONSDIR', './storage/app/submissoins');
        $SIMEXEC = $SIMDIR . '/' . $simLangMapArr[$lang];

        foreach($relatedSubmissionObj as $relatedSubmission)
        {
            /* Run SIM Check Here */
            unset($result);

            /* Do not check with itself */
            if($relatedSubmission->runid == $currentSubmissionObj->runid)
                continue;

            /* First Do Similarity Percentage Check */

            $SIM_PERCENTAGE_COMMAND = $SIMEXEC . ' -p ' . $SUBMISSIONSDIR.'/'. $currentSubmissionObj->submit_file . ' ' . $SUBMISSIONSDIR . '/' . $relatedSubmission->submit_file . " > /tmp/sim";
            exec($SIM_PERCENTAGE_COMMAND, $result);

            /* The first Percentage in the output is what we need */
            $sim_data = file_get_contents('/tmp/sim');
            //echo $SIM_PERCENTAGE_COMMAND . '<br/>';
            //echo $sim_data;
            $pos = strpos($sim_data, "consists for");

            if($pos != NULL)
            {
                $pos_end = strpos($sim_data, "%");
                $tmpstr = substr($sim_data, $pos, $pos_end - $pos + 1);
                $pattern = "/\d+/";
                $resultarr = [];
                preg_match($pattern, $tmpstr, $resultarr);
                $similarity = intval($resultarr[0]);
                if($max_similarity < $similarity)
                {
                    $max_similarity = $similarity;
                    $max_similarity_runid = $relatedSubmission->runid;
                }
            }
        }
        if($max_similarity >= 80)
        {
            $simObj = new Sim;
            $simObj->runid = $currentSubmissionObj->runid;
            $simObj->sim_runid = $max_similarity_runid;
            $simObj->similarity = $max_similarity;
            if(Sim::where('runid', $currentSubmissionObj->runid)->first() == NULL)
            {
                $simObj->save();
            }
            else
            {
                /* Whether this code is buggy or not remains unsure */
                Sim::where('runid', $currentSubmissionObj->runid)->update([
                    'sim_runid' => $max_similarity_runid,
                    'similarity' => $max_similarity
                ]);
            }

            $sim_file_name = Submission::find($max_similarity_runid)->submit_file;

            $SIM_DIFF_COMMAND = $SIMEXEC . ' ' . $SUBMISSIONSDIR.'/'. $currentSubmissionObj->submit_file . ' ' . $SUBMISSIONSDIR . '/' . $sim_file_name . " > /tmp/sim_diff";
            exec($SIM_DIFF_COMMAND);
            $sim_diff = file_get_contents('/tmp/sim_diff');
            Storage::put('sim/' . $simObj->runid . '_' . $simObj->sim_runid . '.sim', $sim_diff);
        }

        /* Cleanup the temp file */
        exec('rm -rf /tmp/sim');
        exec('rm -rf /tmp/sim_diff');
        return ;
    }

}
