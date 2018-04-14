<?php

namespace App\Http\Controllers;

use App\OJLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Validator;
use Storage;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Problem;
use App\User;
use App\Submission;
use App\ContestProblem;
use App\ContestUser;
use App\Contest;
use App\ContestBalloon;

class SubmissionController extends Controller
{
    /**
     * @function submitAction
     * @input $request,$problem_id
     *
     * @return Redirect
     * @description Check If the input is valid,
     *              if valid,save submission information to database and save submitted code to file
     *              Redirect to status page of submitted problem
     *              else redirect to previous page with error message
     */
    public function submitAction(Request $request, $problem_id)
    {
        if($request->method() == "POST")
        {
            $langsufix = [
                "C" => "c",
                "Java" => "java",
                "C++11" => "cc",
                "C++" => "cpp",
                "Python2" => "py2",
                "Python3" => "py3",
            ];

            $vdtor = Validator::make($request->all(),[
                "code" => "required|min:50|max:50000",
            ]);
            //var_dump($request->input());
            //var_dump($request->session()->all());
            $uid = $request->session()->get('uid');
            $fileName = "";
            $submission = new Submission;
            if($vdtor->fails())
            {
                return Redirect::to($request->server('HTTP_REFERER'))
                    ->withErrors($vdtor);
            }
            $fileName = $uid."-".$problem_id."-".time().".".$langsufix[$request->input('lang')];
            echo $fileName;
            Storage::put("submissions/".$fileName, $request->input('code'));
            $submission->pid = $problem_id;
            $submission->uid = $uid;
            $submission->cid = 0;
            $submission->lang = $request->input('lang');
            $submission->result = "Pending";
            $submission->submit_time = date('Y-m-d-H:i:s');
            $submission->submit_file = $fileName;
            $submission->md5sum = md5($request->input('code'));
            $submission->judge_status = 0;
            $submission->save();
            $runid = $submission->id;
            return Redirect::to("/status/$runid")->withcookie('lang',$submission->lang);
        }
    }

    /**
     * @function getSubmissionByID
     * @input $request,$run_id
     *
     * @return View
     * @description get submission information from database and show submission by given $run_id
     */
    public function getSubmissionByID(Request $request, $run_id)
    {
        $input = $request->all();
        $data = [];
        $submissionObj = Submission::where('runid', $run_id)->first();
        $data = $submissionObj;
        if (Storage::exists("submissions/" . $submissionObj->submit_file)) {
            $fileContent = Storage::get("submissions/" . $submissionObj->submit_file);
            $data->code = $fileContent;
        } else
            $data->code = "can't find submission file!";
        /* It's in contest */
        if(isset($input['c']))
        {
            $contestObj = Contest::where('contest_id', $input['c'])->first();
            $data->contest = $contestObj;
            $data->contestProblemId = $input['p'];
        }

        return View::make('status.index', $data);
    }

    /**
     * @function getSubmission
     * @input $request
     *
     * @return Redirect 
     * @description Just Redirect to /status/p/1
     */
    public function getSubmission(Request $request)
    {
        return Redirect::to('/status/p/1');
    }

    /**
     * @function getSubmissionListByPageID
     * @input $request,$page_id
     *
     * @return View
     * @description get submission list from database and show problems of given $page_id
     */
    public function getSubmissionListByPageID(Request $request, $page_id)
    {
        $itemsPerPage = 30;
        $data = [];
        $data['submissions'] = NULL;
        $input = $request->all();
        $queryArr = [];

        foreach($input as $key => $val)
        {
            if($val == "All" || $val == "")
                continue;
            if($key != "username")
                $queryArr[$key] = $val;
            if($key == "username")
            {
                $userObj = User::where('username', $val)->first();
                if($userObj != NULL)
                {
                    $queryArr['uid'] = $userObj->uid;
                }
                else
                {
                    $queryArr['uid'] = "Th11EN412am#eN%o0neCanCreEte";
                }
            }
        }
        $submissionObj = Submission::with('user', 'user.info', 'problem')->where($queryArr);
        $submissionObjCount = $submissionObj->count();
        $submissionObj = $submissionObj->orderby('runid', 'desc')
            ->skip(($page_id - 1) * $itemsPerPage)
            ->take($itemsPerPage)
            ->get();
        for ($count = 0; $count < $submissionObj->count(); $count++)
        {
            $data['submissions'][$count] = $submissionObj[$count];
            $problemTitle = $submissionObj[$count]->problem->title;
            $username = $submissionObj[$count]->user->username;
            $data['submissions'][$count]->userName = $username;
            $data['submissions'][$count]->nickname = $submissionObj[$count]->user->info->nickname;
            $data['submissions'][$count]->problemTitle = $problemTitle;
        }
        $queryInput = $request->input();
        $queryStr = "?";
        foreach($queryInput as $key => $val)
        {
            $queryStr .= $key . "=" . $val . "&";
        }
        if (($page_id) * $itemsPerPage >= $submissionObjCount)
        {
            $data['lastPage'] = true;
        }
        if($page_id == 1)
        {
            $data['firstPage'] = true;
        }
        $data['page_id'] = $page_id;
        $data['queryStr'] = $queryStr;
        return View::make('status.list', $data);
    }

    /**
     * @function contestSubmitAction
     * @input $request,$contest_id,$problem_id
     *
     * @return View or Redirect
     * @description Check whether the contest is running,
     *              if not running, show error/contest_end page
     *              if is running, check if the input is valid
     *              if valid, insert contestproblem submission information into database and save submitted code to file
     *              redirect to contest status page
     *              else redirect to previous page with error message
     */
    public function contestSubmitAction(Request $request, $contest_id, $problem_id)
    {
        if($request->method() == "POST")
        {
            $data = [];

            $contestProblemObj = ContestProblem::where([
                'contest_id' => $contest_id,
                'contest_problem_id' => $problem_id
            ])->first();
            $contestObj = Contest::where('contest_id', $contest_id)->first();
            $data['contest'] = $contestObj;
            if(!$contestObj->isRunning())
            {
                //if(!(session('uid') && session('uid') <= 2))
                if(!roleController::is("admin"))
                    return View::make("errors.contest_end", $data);
            }

            $realProblemID = $contestProblemObj->problem_id;

            $langsufix = [
                "C" => "c",
                "Java" => "java",
                "C++11" => "cc",
                "C++" => "cpp",
            ];

            $vdtor = Validator::make($request->all(),[
                "code" => "required|min:50|max:50000",
            ]);
            var_dump($request->input());
            var_dump($request->session()->all());
            $uid = $request->session()->get('uid');
            $fileName = "";
            $submission = new Submission;
            if($vdtor->fails())
            {
                return Redirect::to($request->server('HTTP_REFERER'))
                    ->withErrors($vdtor);
            }
            $fileName = $contest_id . "-" . $uid."-".$problem_id."-".time().".".$langsufix[$request->input('lang')];
            echo $fileName;
            Storage::put("submissions/".$fileName, $request->input('code'));
            $submission->pid = $realProblemID;
            $submission->uid = $uid;
            $submission->cid = $contest_id;
            $submission->lang = $request->input('lang');
            $submission->result = "Pending";
            $submission->submit_time = date('Y-m-d-H:i:s');
            $submission->submit_file = $fileName;
            $submission->md5sum = md5($request->input('code'));
            $submission->judge_status = 0;
            $submission->save();
            $runid = $submission->id;
            return Redirect::to("/contest/$contest_id/status/");
        }
    }

    /**
     * @function getSubmissionJSONByRunID
     * @input $request
     *
     * @return jsonObj
     * @description API Interface for Ajax use
     */
    public function getSubmissionJSONByRunID(Request $request)
    {
        $data = [];
        $input = $request->all();
        if(!isset($input['run_id']))
            return null;

        $run_id = $input['run_id'];

        $submissionObj = Submission::find($run_id);
        if($submissionObj->cid != 0)
        {
            $submissionObj->cpid = ContestProblem::where([
                "contest_id" => $submissionObj->cid,
                "problem_id" => $submissionObj->pid
            ])->first()->contest_problem_id;
        }


        return json_encode($submissionObj);
    }

    /**
     * @function rejudgeSubmissionByContestIDAndProblemID
     * @input $request,$contest_id,$contest_problem_id
     *
     * @return null
     * @description reset contestproblem and submmition of the problem's state
     */
    public function rejudgeSubmissionByContestIDAndProblemID(Request $request, $contest_id, $problem_id)
    {
        $contestProblemObj = ContestProblem::where([
            'contest_id' => $contest_id,
            'contest_problem_id' => $problem_id
        ])->first();

        // contest_id == 0 means problem_id is RealProblemID
        if($contest_id == 0)
            $realProblemID = $problem_id;
        else
            $realProblemID = $contestProblemObj->problem_id;

        // If contest_id == 0 , judge all submissions including contest
        if($contest_id == 0)
        {
            $submissionObj = Submission::where([
                "pid" => $realProblemID
            ]);
        }
        else
        {
            $submissionObj = Submission::where([
                "cid" => $contest_id,
                "pid" => $realProblemID
            ]);
        }

        if($contest_id != 0)
        {
            $contestProblemObj->first_ac = 0;
            $contestProblemObj->save();
        }

        foreach($submissionObj->get() as $submission)
        {
            if($contest_id != 0 && $submission->result == 'Accepted')
            {
                $contestBalloon = ContestBalloon::all();
                foreach($contestBalloon as $contestBalloonObj)
                {
                    if($contestBalloonObj->runid == $submission->runid)
                    {
                        $contestBalloonObj->balloon_status = 1;
                        $contestBalloonObj->save();
                        break;
                    }
                }

            }
            $submission->judge_status = 0;
            $submission->result = "Rejudging";
            $submission->save();
        }

        if($contest_id == 0)
            return Redirect::to("/status/");
        $uid = $request->session()->get('uid');
        OJLog::rejudge($uid, $contest_id, $problem_id);
        return Redirect::to($request->server('HTTP_REFERER'));
    }

    /**
     * @function rejudgeSubmissionByRunID
     * @input $request $run_id
     *
     * @return Redirect
     * @description: A simple (stub) Rejudge by RunID Function
     *               See comments inline for details
     */
    public function rejudgeSubmissionByRunID(Request $request, $run_id)
    {
        $submissionObj = Submission::find($run_id);
        $submissionObj->result = "Rejudging";

        /* Now just simply fake the judge host, not real rejudge */
        $submissionObj->judge_status = 0;

        /*
         * Bugs! If this user is FB in one contest, after rejudge its result
         * turns to error, but FB Flag will not chaged
         */
        $submissionObj->save();
        $uid = $request->session()->get('uid');
        OJLog::rejudgeByRunID($uid, $run_id);
        return Redirect::to($request->server('HTTP_REFERER'));
    }

    public function getSim(Request $request)
    {
        $input = $request->all();
        if(!isset($input['left']) || !isset($input['right']))
            abort(404);
        $left = $input['left'];
        $right = $input['right'];
        $submissionObj = Submission::find($left);
        if($submissionObj != NULL)
        {
            $leftUserObj = User::find($submissionObj->uid);
            $rightUserObj = User::find(Submission::find($input['right'])->uid);
            $data['leftUser'] = $leftUserObj;
            $data['rightUser'] = $rightUserObj;
            $data['lcode'] = Storage::get('submissions/' . $submissionObj->submit_file);
            $data['rcode'] = Storage::get('submissions/' . Submission::find($input['right'])->submit_file);

            /* left and right is very Similar */
            if($submissionObj->sim != NULL && $submissionObj->sim->sim_runid == $right)
            {
                $data['sim'] = $submissionObj->sim;
                /* Admin can download sim */
                $data['sim_diff'] = $left . '_' . $right . '.sim';
            }
            return View::make('status.sim', $data);
        }
        else
        {
            abort(404);
        }
    }
}
