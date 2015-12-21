<?php

namespace App\Http\Controllers;

use App\Contest;
use App\ContestProblem;
use App\Problem;
use App\User;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\MessageBag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Response;
//use App\Submission;

class ContestController extends Controller
{
    public function showContestDashboard(Request $request)
    {
        $contestObj = Contest::all();
        var_dump($contestObj);
        $data = [];
        if(!$contestObj->isEmpty())
        {

        }
        return View::make('contest.dashboard', $data);
    }

    public function addContest(Request $request)
    {
        $data = [];
        $errMsg = new MessageBag;
        if($request->method() == "POST")
        {
            $input = $request->all();
            $currentProblem = [];
            //Validation Check

            $beginTime = strtotime($input['begin_time']);
            $endTime = strtotime($input['end_time']);
            $vdtor = Validator::make($input, [
                'contest_name' => 'required | unique:contest_info',
                'problem_id' => 'exists:problems'
            ]);
            if($vdtor->fails())
            {
                $errMsg = $vdtor->errors();
            }
            if($beginTime >= $endTime)
            {
                $errMsg->add('time', "Contest ends before the contest begin");
            }
            for($i = 0; $i < count($input['problem_id']); $i++)
            {
                $currentProblem[$i]['problem_id'] = $input['problem_id'][$i];
                $currentProblem[$i]['problem_name'] = $input['problem_name'][$i];
            }
            if(!$errMsg->isEmpty())
            {
                var_dump($errMsg);
                //Use Redirect route to flash old data into form
                return Redirect::route('contest.add')->withErrors($errMsg)->withInput($input)->with([
                    'problem_count' => count($input['problem_id']),
                    'current_problem' => $currentProblem,
                ]);
            }
            $contestSaveData = [];
            $contestSaveData['contest_name']= $input['contest_name'];
            $contestSaveData['begin_time'] = date('Y-m-d H:i:s', $beginTime);
            $contestSaveData['end_time'] = date('Y-m-d H:i:s', $endTime);
            $contestSaveData['admin_id']= $request->session('uid');
            var_dump($input);
            var_dump($contestSaveData);
            //Check the problems, if pid is not unique, only insert one
            $checkUnique = [];
            $contestProblemSaveData = [];
            for($i = 0; $i < count($input['problem_id']); $i++)
            {
                $problem_id = $input['problem_id'][$i];
                if(isset($checkUnique[$problem_id]))
                    continue;
                $checkUnique[$problem_id] = 1;

            }
            echo $input['begin_time'];
            $beginTime = strtotime($input['begin_time']);
            echo date('Y-m-d H:i:s', $beginTime);
        }
        return View::make('contest.add', $data);
    }
}


