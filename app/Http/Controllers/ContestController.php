<?php

namespace App\Http\Controllers;

use App\Contest;
use App\ContestBalloonEvent;
use App\ContestBalloon;
use App\ContestProblem;
use App\ContestUser;
use App\Problem;
use App\Submission;
use App\User;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Controller;
use App\Userinfo;
use Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\MessageBag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Response;

class ContestUserInfo
{
    public $userObj;
    public $result = [];
    public $time = [];
    public $penalty = [];
    public $realPenalty = [];
    public $totalPenalty = 0;
    public $totalAC = 0;
}


class ContestController extends Controller
{
    /*
     * @function showContestDashboard
     * @input $request
     *
     * @return View
     * @description: A redirect to /dashboard/contest/1
     *
     */
    public function showContestDashboard(Request $request)
    {
        return Redirect::to('/dashboard/contest/p/1');
    }

    /*
     * @function showContestDashboardByPageID
     * @input $request $page_id
     *
     * @return View
     * @description show contest list in page, return the View
     */
    public function showContestDashboardByPageID(Request $request, $page_id)
    {
        $data = [];
        $contestPerPage = 20;
        $data = Contest::getContestItemsInPage($contestPerPage, $page_id);

        return View::make('contest.dashboard', $data);
    }

    /*
     * @function addContest
     * @input $request
     *
     * @return View or Redirect
     * @description Check If the input is valid
     *              if valid , insert the contest info into database
     *              redirect back to dashboard, else, redirect to
     *              addContestPage with ErrMsg
     */
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
            $registerBeginTime = strtotime($input['register_begin_time']);
            $registerEndTime = strtotime($input['register_end_time']);
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
            if($input['contest_type'] == "register")
            {
                if($registerBeginTime >= $registerEndTime)
                {
                    $errMsg->add('time', "Register ends before the register begin");
                }
                if($registerBeginTime >= $beginTime || $registerEndTime >= $endTime)
                {
                    $errMsg->add('time', "Register begins or end after the contest begin");
                }
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
            if($type == 2)
            {
                $contestObj->register_begin_time = $input['register_begin_time'];
                $contestObj->register_end_time = $input['register_end_time'];
            }
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
                $contestProblemObj->contest_id = $contestObj->contest_id;
                $contestProblemObj->problem_title = $input['problem_name'][$i];
                $contestProblemObj->contest_problem_id = $i + 1;
                $contestProblemObj->save();

                //We should make sure this problem is disabled in normal mode

                $problemObj = Problem::where('problem_id', $contestProblemObj->problem_id);
                $problemObj->update([
                    "visibility_locks" => $problemObj->first()->visibility_locks + 1
                ]);
            }
            if($type == 1)
            {
                $userList = explode(',', $input['user_list']);
                $userList = array_unique($userList);
                foreach($userList as $user)
                {
                    $contestUserObj = new ContestUser;
                    $contestUserObj->contest_id = $contestObj->contest_id;
                    $contestUserObj->username = trim($user);
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

    /*
     * @function getContest
     * @input $request
     *
     * @return Redirect
     * @description Just Redirect to /contest/p/1
     */
    public function getContest(Request $request)
    {
        return Redirect::to('/contest/p/1');
    }

    /*
     * @function getContestListByPageID
     * @input $request $page_id
     *
     * @return View
     * @description get the contest list by given a page_id
     *              and return a view
     */
    public function getContestListByPageID(Request $request, $page_id)
    {
        $data = [];
        $contestPerPage = 20;
        $data = Contest::getContestItemsInPage($contestPerPage, $page_id);

        return View::make('contest.list', $data);
    }

    /*
     * @function getContestByID
     * @input $request $contest_id
     *
     * @return View or Redirect
     * @description get the contest index page by given $contest_id
     *              if the user is not supposed to access the contest
     *              Redirect him back
     */
    public function getContestByID(Request $request, $contest_id)
    {
        $roleController = new RoleController();
        $data = [];
        $uid = $request->session()->get('uid');
	    $userObj = User::where('uid', $uid)->first();
	    //var_dump($userObj);
        $contestUserObj = ContestUser::where('username', $userObj->username)->first();
        if($contestUserObj)
            $username = $contestUserObj->username;
        else
            $username = "";
        $contestObj = Contest::where('contest_id', $contest_id)->first();
        if($contestObj->contest_type == 1 || $contestObj->contest_type == 2)
        {
            if(!$roleController->is("admin"))
            {
                $contestUserObj = ContestUser::where([
                    'username' => $username,
                    'contest_id' => $contest_id
                ])->first();
                if ($contestUserObj == NULL || $contestUserObj->username == NULL)
                    return Redirect::to('/contest/p/1');
            }
        }
        $data["contest"] = $contestObj;
        $contestProblemObj = ContestProblem::where('contest_id', $contest_id)->orderby('contest_problem_id', 'asc')->get();
        $count = 0;
        //var_dump($contestProblemObj);
        // Fetch Each Problem and Get The Submission Status
        // Get user AC status and FB Status
        foreach($contestProblemObj as $contestProblem)
        {
            $data["problems"][$count] = $contestProblem;
            $data["problems"][$count]->contest_problem_id = $contestProblem->contest_problem_id;
            $realProblemID = $contestProblem->problem_id;
            $data["problems"][$count]->totalSubmissionCount = Submission::where([
                "pid" => $realProblemID,
                "cid" => $contest_id,
            ])->count();

            $data["problems"][$count]->acSubmissionCount = Submission::where([
                "pid" => $realProblemID,
                "cid" => $contest_id,
                "result" => "Accepted",
            ])->count();


            $data["problems"][$count]->realProblemName = Problem::getProblemTitle($realProblemID);

            if($uid == $contestProblem->first_ac)
            {
                $data["problems"][$count]->thisUserFB = true;
            }

            if(Submission::where([
                "pid" => $realProblemID,
                "cid" => $contest_id,
                "uid" => $uid,
                "result" => "Accepted",
            ])->count())
            {
                $data["problems"][$count]->thisUserAc = true;
            }
            $count++;
        }

        if($contestObj->isRunning())
            $data['contest']->status = "Running";
        if($contestObj->isPending())
            $data['contest']->status = "Pending";
        if($contestObj->isEnded())
            $data['contest']->status = "Ended";
        //var_dump($data['problems']);
        return View::make('contest.index', $data);

    }

    /*
     * @function getContestRanklist
     * @input $request $contest_id
     *
     * @return View
     * @description calculate and give out the contest ranklist by $contest_id
     *              use php-builtin usort for sorting structures
     */
    public function getContestRanklist(Request $request, $contest_id)
    {
        $data = [];

        $submissionObj = Submission::where('cid', $contest_id)->get();
        $contestObj = Contest::where('contest_id', $contest_id)->first();

        $count = 0;

        //fetch all user and ensure it's unique

        //This is a array to check the existence of certain user

        $user_exist_arr = [];

        foreach($submissionObj as $submission)
        {
            $userObj = User::where('uid', $submission->uid)->first();
            if(!isset($user_exist_arr[$userObj->uid]))
            {
                $data['users'][$count] = User::where('uid', $submission->uid)->first();
                $user_exist_arr[$submission->uid] = 1;
                $count++;
            }
        }


        $data['problems'] = ContestProblem::where('contest_id', $contest_id)->get();

        //for($i = 0; $i < $count; $i++)
	    if(!isset($data['users']))
		    $data['users'] = [];
        foreach($data['users'] as $user)
        {
            //$user = $data['user'][$i];
            $submissionObj = Submission::where([
                'cid' => $contest_id,
                'uid' => $user->uid
            ])->orderby('runid', 'asc')->get();

            $user->infoObj = new ContestUserInfo();
            foreach($submissionObj as $submission)
            {
                $contestProblemObj = ContestProblem::where([
                    "contest_id" => $contest_id,
                    "problem_id" => $submission->pid
                ])->first();
                $contestProblemID = $contestProblemObj->contest_problem_id;
                $currentResult = $submission->result;
                if($currentResult != "Accepted" && $currentResult != "Pending" && $currentResult != "Rejudging")
                {
                    if(!isset($user->infoObj->penalty[$contestProblemID]))
                        $user->infoObj->penalty[$contestProblemID] = 1;
                    else
                        $user->infoObj->penalty[$contestProblemID]++;
                    $user->infoObj->result[$contestProblemID] = $submission->result;
                }
                if($currentResult == "Accepted")
                {
                    if(isset($user->infoObj->result[$contestProblemID])  && ($user->infoObj->result[$contestProblemID] == "Accepted" || $user->infoObj->result[$contestProblemID] == "First Blood"))
                    {
                        //Do nothing
                    }
                    else
                    {
                        //Check FB

                        if (ContestProblem::where(["contest_id" => $contest_id, "contest_problem_id" => $contestProblemID,])->first()->first_ac == $user->uid)
                        {
                            $user->infoObj->result[$contestProblemID] = "First Blood";
                        }
                        else
                        {
                            $user->infoObj->result[$contestProblemID] = "Accepted";
                        }
                        //$user->infoObj->time[$contestProblemID] = strtotime($submission->submit_time ,strtotime($contestObj->begin_time));
$user->infoObj->time[$contestProblemID] =  strtotime($submission->submit_time) - strtotime($contestObj->begin_time);
//echo date('y-m-d H:i:s', $user->infoObj->time[$contestProblemID]);
                        //$user->infoObj->time[$contestProblemID] = date('H:i:s', $user->infoObj->time[$contestProblemID]);
                        if(!isset($user->infoObj->penalty[$contestProblemID]))
                            $user->infoObj->penalty[$contestProblemID] = 0;
                        //$user->infoObj->realPenalty[$contestProblemID] = date('Y-m-d H:i:s', $user->infoObj->time[$contestProblemID] + 20 * $user->infoObj->penalty[$contestProblemID]);
                        $user->infoObj->realPenalty[$contestProblemID] = $user->infoObj->time[$contestProblemID] + 20 * 60 * $user->infoObj->penalty[$contestProblemID];
                        $user->infoObj->totalPenalty += $user->infoObj->realPenalty[$contestProblemID];
                        $user->infoObj->totalAC ++;
                    }
                }
            }
            $userInfoObj = Userinfo::where('uid', $user->uid)->first();
            $user->nick_name = $userInfoObj->nickname;
        }
        usort($data['users'], ['self', "cmp"]);
        $data['contest_id'] = $contest_id;
        $data['counter'] = 1;
        return View::make('contest.ranklist', $data);
    }

    /*
     * @function cmp
     * @input $userA $userB
     *
     * @return bool
     * @description sort logic for usort in getContestRanklist
     */
    public function cmp($userA, $userB)
    {
        if($userA->infoObj->totalAC == $userB->infoObj->totalAC)
        {
            return $userA->infoObj->totalPenalty > $userB->infoObj->totalPenalty;
        }
        return $userA->infoObj->totalAC < $userB->infoObj->totalAC;
    }

    /*
     * @stub getContestRanklistByPageID
     */
    public function getContestRanklistByPageID(Request $request, $contest_id, $page_id)
    {
        $data = [];

        return View::make('contest.ranklist', $data);
    }

    /*
     * @function getContestStatus
     * @input $request $contest_id
     *
     * @return Redirect
     * @description Redirect to the url given
     */
    public function getContestStatus(Request $request, $contest_id)
    {
        return Redirect::to("/contest/$contest_id/status/p/1");
    }

    /*
     * @function getContestStatusByPageID
     */
    public function getContestStatusByPageID(Request $request, $contest_id, $page_id)
    {
        //Almost same as getSubmissionListByPageID, will change in future
        $data = [];

        $itemsPerPage = 50;
        $data['submissions'] = NULL;
        $input = $request->all();
        $queryArr = [];
        $queryArr['cid'] = $contest_id;
        $contestObj = Contest::where('contest_id', $contest_id)->first();

        $data['contest'] = $contestObj;

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
        $submissionObj = Submission::where($queryArr)->orderby('runid', 'desc')->get();

        for($count = 0, $i = ($page_id - 1) * $itemsPerPage; $count < $itemsPerPage && $i < $submissionObj->count(); $i++, $count++)
        {
            $data['submissions'][$count] = $submissionObj[$i];
            $tmpUserObj = User::where('uid', $submissionObj[$i]->uid)->first();
            $tmpProblemObj = ContestProblem::where([
                "contest_id" => $contest_id,
                "problem_id" => $submissionObj[$i]->pid
            ])->first();
            $problemTitle = $tmpProblemObj['problem_title'];
            $username = $tmpUserObj['username'];
            $data['submissions'][$count]->userName = $username;
            $data['submissions'][$count]->problemTitle = $problemTitle;
        }

        //$data['submissions'] = $submissionObj;
        $queryInput = $request->input();
        $queryStr = "?";
        foreach($queryInput as $key => $val)
        {
            $queryStr .= $key . "=" . $val . "&";
        }
        $data['page_id'] = $page_id;
        $data['queryStr'] = $queryStr;
        if($page_id == 1)
        {
            $data['firstPage'] = 1;
        }
        if($i >= $submissionObj->count())
        {
            $data['lastPage'] = 1;
        }
        return View::make('status.list', $data);
    }

    /*
     * @function setContest
     * @input $request,$contest_id
     *
     * @return view or redirect
     * @description edit contest, called when edit button in /dashboard/contest is clicked 
     *  or submit button in edit page is clicked 
     */

    public function setContest(Request $request, $contest_id)
    {
        $data = [];
        $contestObj = Contest::where('contest_id', $contest_id)->first();
        $contestProblemObj = ContestProblem::where('contest_id',$contest_id)->get();
        if($request->method() == "POST")
        {
            $input = $request->all();
            //Validation Check
            $vdtor = Validator::make($input, [
                'contest_name' => 'required | unique:contest_info',
                'problem_id' => 'required | exists:problems'
            ]);
            if($vdtor->fails())
            {
                $errMsg = $vdtor->errors();
            }
            //$beginTime = strtotime($input['begin_time']);
            //$endTime = strtotime($input['end_time']);
            $contestObj = Contest::where('contest_id', $contest_id)->first();
            var_dump($contestObj->primaryKey);
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
            if($type == 2)
            {
                $contestObj->register_begin_time = $input['register_begin_time'];
                $contestObj->register_end_time = $input['register_end_time'];
            }
            var_dump($contestObj->contest_id);
            $contestObj->save();

            for($i = 0;$i < count($contestProblemObj);$i++)
            {
                $problemObj = Problem::where('problem_id', $contestProblemObj[$i]->problem_id);
                $problemObj->update([
                    "visibility_locks" => $problemObj->first()->visibility_locks - 1
                ]);
            }
            ContestProblem::where('contest_id', $contest_id)->delete();
            $checkUnique = [];
            for($i = 0; $i < count($input['problem_id']); $i++)
            {
                $problem_id = $input['problem_id'][$i];
                if(isset($checkUnique[$problem_id]))
                    continue;
                $checkUnique[$problem_id] = 1;
                $contestProblemObj = new ContestProblem;
                $contestProblemObj->problem_id = $problem_id;
                $contestProblemObj->contest_id = $contestObj->contest_id;
                $contestProblemObj->problem_title = $input['problem_name'][$i];
                $contestProblemObj->contest_problem_id = $i + 1;
                $contestProblemObj->save();

                $problemObj = Problem::where('problem_id', $contestProblemObj->problem_id);
                $problemObj->update([
                    "visibility_locks" => $problemObj->first()->visibility_locks + 1
                ]);
            }
            if($type == 1)
            {
                $userList = explode(',', $input['user_list']);
                $userList = array_unique($userList);
                ContestUser::where('contest_id',$contest_id)->delete();
                foreach($userList as $user)
                {
                    $contestUserObj = new ContestUser;
                    $contestUserObj->contest_id = $contestObj->contest_id;
                    $contestUserObj->username = trim($user);
                    $userObj = User::where('username', $user)->first();
                    if($userObj)
                    {
                        $contestUserObj->user_id = $userObj->uid;
                    }
                    $contestUserObj->save();
                }
            }
            return Redirect::to("/dashboard/contest");

        }
        else
        {
            $data["contest"] = $contestObj;
            $data["contestProblem"] = $contestProblemObj;
            $data["problem_count"] = count($contestProblemObj);
            if($contestObj->contest_type == 1)
            {
                $contestUserObj = ContestUser::where('contest_id',$contest_id)->get();
                $data["contestUser"] = $contestUserObj;
            }
            return View::make('contest.set',$data);

        }
    }

    /*
     * @function deleteContest
     * @input $request,$contest_id
     *
     * @return Redirect
     * @description deleteContest
     */
    public function deleteContest(Request $request, $contest_id)
    {
        ContestUser::where('contest_id',$contest_id)->delete();
        ContestProblem::where('contest_id',$contest_id)->delete();
        Contest::where('contest_id',$contest_id)->delete();
        return Redirect::to("/dashboard/contest");
    }

    /*
     * @function registerContest
     * @input $request, $contest_id
     *
     * @return Redirect or View
     * @description register contest before the contest is started.
     *              If the contest isn't a register contest, redirect to contest list page
     */
    public function registerContest(Request $request, $contest_id)
    {
        $data = [];
        $data['contest_id'] = $contest_id;
        $contestObj = Contest::where('contest_id', $contest_id)->first();
        if(!isset($contestObj))
            return Redirect::to('/contest/p/1');
        if($contestObj->contest_type != 2)
            return Redirect::to('/contest/p/1');
        if(!$contestObj->isInRegister())
            return Redirect::to('/contest/p/1') ;
        if($request->method() == "POST")
        {
            $input = $request->input();
            $this->validate($request, [
                'captcha' => 'required|captcha'
            ]);
            $uid = $request->session()->get('uid');
            $userObj = User::where('uid', $uid)->first();
            $checkExist = ContestUser::where('contest_id', $contest_id)->where('user_id', $uid)->first();
            if(isset($checkExist))
            {
                return Redirect::to('/contest/p/1');
            }
            $contestUserObj = new ContestUser;
            $contestUserObj->contest_id = $contest_id;
            $contestUserObj->user_id = $uid;
            $contestUserObj->username = $userObj->username;
            $contestUserObj->is_official = 0;
            $contestUserObj->save();
            return Redirect::to('/contest/p/1');
        }
        return View::make('contest.register', $data);
    }

    /*
     *
     * @function getBalloonlist
     * @input $request,$constest_id
     *
     * @return View
     * @description show balloons distribution list
     */
    public function getBalloonlist(Request $request)
    {
        $data = [];
        $input = $request->all();
        $contest_id = $input['cid'];
        $contestBalloonEvent = ContestBalloonEvent::orderby('id', 'desc')->get();
        $count = 0;

        foreach($contestBalloonEvent as $contestBalloonEventObj)
        {
            $submissionObj = Submission::where('runid',$contestBalloonEventObj->runid)->first();
            if($submissionObj->cid == $contest_id) {
                $contestProblemObj = ContestProblem::where([
                    'contest_id' => $submissionObj->cid,
                    'problem_id' => $submissionObj->pid
                ])->first();
                $data[$count]["username"] = User::where('uid', $submissionObj->uid)->first()->username;
                $data[$count]["contest_problem_id"] = $contestProblemObj->contest_problem_id;
                $data[$count]["short_name"] = $contestProblemObj->problem_title;
                $data[$count]["color"] = $contestProblemObj->problem_color;
                if($contestBalloonEventObj->event_status == env('BALLOON_SEND',1))
                {
                    $data[$count]["event"] = 'send';
                }
                else
                {
                    $data[$count]["event"] = 'discard';
                }
                $count++;
            }
        }
        $data["count"] = $count;
        return json_encode($data);
    }

    /*
     * @function getContestBalloonView
     * @input $request $contest_id
     *
     * @return View
     * @description return the ContestBalloon Event By contest_id
     */
    public function getContestBalloonView(Request $request, $contest_id)
    {
        $data = [];
        $data['contest_id'] = $contest_id;

        return View::make('contest.balloon', $data);
    }

}


