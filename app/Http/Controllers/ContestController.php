<?php

namespace App\Http\Controllers;

use App\Contest;
use App\ContestProblem;
use App\ContestUser;
use App\Problem;
use App\User;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\MessageBag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Response;

class ContestController extends Controller
{
    public function showContestDashboard(Request $request)
    {
        $contestObj = Contest::all();
        $data = [];
        if(!$contestObj->isEmpty())
        {
            $data['contests'] = $contestObj;
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
                'problem_id' => 'required | exists:problems'
            ]);
            if($vdtor->fails())
            {
                $errMsg = $vdtor->errors();
            }
            //No Problem Provided, Redirect Now
            if(!isset($input['problem_id']))
            {
                $errMsg->add('err', "You must Provide at least one problem!");
                return Redirect::route('contest.add')->withErrors($errMsg)->withInput($input);
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
            $contestObj = new Contest;
            $contestObj->contest_name = $input['contest_name'];
            $contestObj->begin_time = $input['begin_time'];
            $contestObj->end_time = $input['end_time'];
            $contestObj->admin_id = $request->session()->get('uid');
            $type = 0;
            switch($input['contest_type'])
            {
                case "public":
                    $type = 0;
                    break;
                case "private":
                    $type = 1;
                    break;
                case "register":
                    $type = 2;
                    break;
            }
            $contestObj->contest_type = $type;
            $contestObj->save();

            //Check the problems, if pid is not unique, only insert one
            $checkUnique = [];
            for($i = 0; $i < count($input['problem_id']); $i++)
            {
                $problem_id = $input['problem_id'][$i];
                if(isset($checkUnique[$problem_id]))
                    continue;
                $checkUnique[$problem_id] = 1;
                $contestProblemObj = new ContestProblem;
                $contestProblemObj->problem_id = $problem_id;
                $contestProblemObj->contest_id = $contestObj->id;
                $contestProblemObj->problem_title = $input['problem_name'][$i];
                $contestProblemObj->contest_problem_id = $i + 1;
                $contestProblemObj->save();
            }
            if($type == 1)
            {
                $userList = explode(',', $input['user_list']);
                $userList = array_unique($userList);
                foreach($userList as $user)
                {
                    $contestUserObj = new ContestUser;
                    $contestUserObj->contest_id = $contestObj->id;
                    $contestUserObj->username = $user;
                    $userObj = User::where('username', $user)->first();
                    if($userObj)
                    {
                        $contestUserObj->user_id = $userObj->uid;
                    }
                    $contestUserObj->save();
                }
            }
        }
        return View::make('contest.add', $data);
    }

    public function getContest(Request $request)
    {
        return Redirect::to('/contest/p/1');
    }

    public function getContestListByPageID(Request $request, $page_id)
    {
        $data = [];

        return View::make('contest.list', $data);
    }

    public function getContestByID(Request $request, $contest_id)
    {
        $data = [];

        return View::make('contest.index', $data);
    }

    public function getContestRanklist(Request $request, $contest_id)
    {
        return Redirect::to("/contest/$contest_id/p/1");
    }

    public function getContestRanklistByPageID(Request $request, $contest_id, $page_id)
    {
        $data = [];

        return View::make('contest.ranklist', $data);
    }

    public function getContestStatus(Request $request, $contest_id)
    {
        return Redirect::to("/contest/$contest_id/status/p/1");
    }

    public function getContestStatusByPageID(Request $request, $contest_id, $page_id)
    {
        $data = [];
        
        return View::make('status.list', $data);
    }

    public function setContest(Request $request, $contest_id)
    {

    }
}


