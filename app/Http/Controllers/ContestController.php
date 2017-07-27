<?php

namespace App\Http\Controllers;

use App\OJLog;
use Event;
use App\Events\ContestPageVisited;
use Cache;
use Carbon\Carbon;
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
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Hash;
use Storage;
use App\ContestRanklist;

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
    /**
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

    /**
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

    /**
     * @function addContest
     * @input $request
     * @uses contest.add.php
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
                $contestProblemObj->problem_color = $input['problem_color'][$i];
                $contestProblemObj->save();

                //We should make sure this problem is disabled in normal mode

                $problemObj = Problem::where('problem_id', $contestProblemObj->problem_id);
                $problemObj->update([
                    "visibility_locks" => 1
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
            OJLog::addContest($contestObj->admin_id, $contestObj->contest_id);
        }
        return View::make('contest.add', $data);
    }

    /**
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

    /**
     * @function getContestListByPageID
     * @input $request $page_id
     *@uses contest.lis.php
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

    /**
     * @function getContestByID
     * @input $request $contest_id
     *@uses contest.index.php
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
        if($contestObj->contest_type == 2)
        {
            if(!$roleController->is("admin"))
            {
                $contestUserObj = ContestUser::where([
                    'username' => $username,
                    'contest_id' => $contest_id
                ])->first();
                if ($contestUserObj == NULL || $contestUserObj->username == NULL)
                {
                    if($contestObj->isInRegister())
                    {
                        return Redirect::to("/contest/$contest_id/register");
                    }
                    return Redirect::to("/contest/p/1");
                }
            }
        }
        if($contestObj->contest_type == 1)
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
            $data["problems"][$count]->totalSubmissionCount = Submission::getValidSubmissionCount($contest_id, $realProblemID);

            $data["problems"][$count]->acSubmissionCount = Submission::where([
                "pid" => $realProblemID,
                "cid" => $contest_id,
                "result" => "Accepted",
            ])->get()->unique('uid')->count();


            $data["problems"][$count]->realProblemName = Problem::getProblemTitle($realProblemID);

            $firstac = $contestObj->getFirstacList();
            if(isset($firstac[$contestProblem->problem_id]) && $uid == $firstac[$contestProblem->problem_id])
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
        Event::fire(new ContestPageVisited($contest_id));
        return View::make('contest.index', $data);

    }

    /**
     * @function getContestRanklist
     * @input $request $contest_id
     *
     *@uses contest.ranklist.php
     * @return View
     * @description calculate and give out the contest ranklist by $contest_id
     *              use php-builtin usort for sorting structures
     */
    public function getContestRanklist(Request $request, $contest_id)
    {
        $roleController = new RoleController();
        /*$uid = $request->session()->get('uid');
        $userObj = User::where('uid', $uid)->first();
        $contestUserObj = ContestUser::where('username', $userObj->username)->first();
        if ($contestUserObj)
            $username = $contestUserObj->username;
        else
            $username = "";*/
        $contestObj = Contest::where('contest_id', $contest_id)->first();
        if (Contest::where('contest_id', $contest_id)->count() == 0)
            return Redirect::to('/contest/p/1');
        /*if ($contestObj->contest_type == 2) {
            if (!$roleController->is("admin")) {
                $contestUserObj = ContestUser::where([
                    'username' => $username,
                    'contest_id' => $contest_id
                ])->first();
                if ($contestUserObj == NULL || $contestUserObj->username == NULL) {
                    return Redirect::to("/contest/p/1");
                }
            }
        }
        if ($contestObj->contest_type == 1) {
            if (!$roleController->is("admin")) {
                $contestUserObj = ContestUser::where([
                    'username' => $username,
                    'contest_id' => $contest_id
                ])->first();
                if ($contestUserObj == NULL || $contestUserObj->username == NULL)
                    return Redirect::to('/contest/p/1');
            }
        }*/

        /** Check for contest status and cache, if contest end and have then load cache*/
        if(Cache::has("contest-$contest_id.ranklist.final"))
        {
            $data = Cache::get("contest-$contest_id.ranklist.final");
            return View::make('contest.ranklist_back', $data);
        }
        /** Check for cache, if have then load cache*/
        $timestamp = (int)(time() / 5);
        if(Cache::has("contest-$contest_id.ranklist.$timestamp"))
        {
            $data = Cache::get("contest-$contest_id.ranklist.$timestamp");
            return View::make('contest.ranklist_back', $data);
        }

        $data = [];

        $contestObj = Contest::where('contest_id', $contest_id)->first();

        /** retrive the count of the active user in the contest */
        $count = 0;

        $data['problems'] = ContestProblem::where('contest_id', $contest_id)->get();

        /** Create a mapping from problem id to contest problem id */
        $problemIDToContestProblemID = [];
        foreach($data['problems'] as $contestProblemObj)
        {
            $problemIDToContestProblemID[$contestProblemObj->problem_id] = $contestProblemObj->contest_problem_id;
        }


        $allSubmissions = Submission::select('uid', 'result', 'pid', 'cid', 'submit_time')->where('cid', $contest_id)
            ->orderby('uid', 'asc')->orderby('runid', 'asc')->get();

        $firstac = $contestObj->getFirstacList();
        $preuid = -1;
        $curUserAcList = [];
        $data["users"] = [];

        foreach($allSubmissions as $submission)
        {
            $contestProblemID = $problemIDToContestProblemID[$submission->pid];
            if ($submission->uid != $preuid)
            {
                /** A new user found */
                $count++;
                $data["users"][$count] = User::limit(1)->where('uid', $submission->uid)->first();
                $userInfoObj = $data["users"][$count]->info;
                $data["users"][$count]->infoObj = new ContestUserInfo();
                $data["users"][$count]->nickname = $userInfoObj->nickname;
                $data["users"][$count]->realname = $userInfoObj->realname;
                $data["users"][$count]->stu_id = $userInfoObj->stu_id;
                $preuid = $submission->uid;
                $curUserAcList = [];

            }

            /** Give current userObj an alias for easy to use */
            $user = &$data["users"][$count];

            /** Only calculate the submission when the user does not AC the problem */
            if(!isset($curUserAcList[$contestProblemID]))
            {
                if($submission->result != "Accepted")
                {
                    if(!isset($user->infoObj->penalty[$contestProblemID]))
                        $user->infoObj->penalty[$contestProblemID] = 1;
                    else
                        $user->infoObj->penalty[$contestProblemID]++;
                    $user->infoObj->result[$contestProblemID] = $submission->result;
                }
                else
                {
                    if($firstac[$submission->pid] == $user->uid)
                    {
                        $user->infoObj->result[$contestProblemID] = "First Blood";
                    } else
                    {
                        $user->infoObj->result[$contestProblemID] = "Accepted";
                    }
                    $curUserAcList[$contestProblemID] = 1;
                    $user->infoObj->time[$contestProblemID] = strtotime($submission->submit_time) - strtotime($contestObj->begin_time);
                    if(!isset($user->infoObj->penalty[$contestProblemID]))
                        $user->infoObj->penalty[$contestProblemID] = 0;
                    $user->infoObj->realPenalty[$contestProblemID] = $user->infoObj->time[$contestProblemID] + 20 * 60 * $user->infoObj->penalty[$contestProblemID];
                    $user->infoObj->totalPenalty += $user->infoObj->realPenalty[$contestProblemID];
                    $user->infoObj->totalAC++;
                }
            }
        }

        usort($data['users'], ['self', "cmp"]);
        /** Add users in private contest whose submission count in contest is 0 */
        if($contestObj->contest_type == 1)
        {
            $contestUserObj = ContestUser::where('contest_id', $contest_id)->get();
            foreach($contestUserObj as $contestUser)
            {
                $uid = 0;
                $username = $contestUser->username;
                $tmpUserObj = User::where('username', $username)->first();
                if($tmpUserObj != NULL)
                {
                    $uid = $tmpUserObj->uid;
                }
                if($allSubmissions->where('uid', $uid)->count() == 0)
                {
                    $count++;
                    /** Simple situation. User exists */
                    $userInfoCount = Userinfo::where('uid', $uid)->count();
                    if ($uid != 0 && $userInfoCount != 0)
                    {
                        $data["users"][$count] = $tmpUserObj;
                        $userInfoObj = $data["users"][$count]->info;
                        $data["users"][$count]->infoObj = new ContestUserInfo();
                        $data["users"][$count]->nickname = $userInfoObj->nickname;
                        $data["users"][$count]->realname = $userInfoObj->realname;
                        $data["users"][$count]->stu_id = $userInfoObj->stu_id;
                    }
                    /** User not exists in users table */
                    else
                    {
                        $tmpUserObj = new User;
                        $tmpUserObj->uid = 0;
                        $tmpUserObj->username = $contestUser->username;
                        $data["users"][$count] = $tmpUserObj;
                        $data["users"][$count]->infoObj = new ContestUserInfo();
                        $data["users"][$count]->nickname = "unregistered";
                        $data["users"][$count]->realname = "unregistered";
                        $data["users"][$count]->stu_id = $contestUser->username;
                    }
                }
            }
        }
        $data['contest_id'] = $contest_id;
        $data['counter'] = 1;
        /** Cache the result for a better performance when multiple visit at the same time */
        if(Contest::where('contest_id', $contest_id)->first()->isEnded())
        {
            Cache::put("contest-$contest_id.ranklist.final", $data, Carbon::now()->addDay());
        }
        else
        {
            Cache::put("contest-$contest_id.ranklist.$timestamp", $data, Carbon::now()->addMinutes(1));
        }
        return View::make('contest.ranklist_back', $data);
    }

    /**
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

    public function getContestRanklist_new(Request $request, $contest_id)
    {
        $contestObj = Contest::where('contest_id', $contest_id)->first();
        if (Contest::where('contest_id', $contest_id)->count() == 0)
            return Redirect::to('/contest/p/1');

        /** Check for contest status and cache, if contest end and have then load cache*/
        if(Cache::has("contest-$contest_id.ranklist.final"))
        {
            $data = Cache::get("contest-$contest_id.ranklist.final");
            return View::make('contest.ranklist', $data);
        }
        /** Check for cache, if have then load cache*/
        $timestamp = (int)(time() / 5);
        if(Cache::has("contest-$contest_id.ranklist.$timestamp"))
        {
            $data = Cache::get("contest-$contest_id.ranklist.$timestamp");
            return View::make('contest.ranklist', $data);
        }

        $data = [];
        $contestRanklistObj = ContestRanklist::where('contest_id', $contest_id)->orderby('rank', 'asc')->get();
        #decode json
        for($i = 0; $i < count($contestRanklistObj); $i++)
        {
            $userObj = User::where('uid', $contestRanklistObj[$i]->uid)->first();
            $userInfoObj = UserInfo::where('uid', $contestRanklistObj[$i]->uid)->first();
            if($userInfoObj == NULL)
            {
                $contestRanklistObj[$i]->nickname = $userObj->username;
                $contestRanklistObj[$i]->realname = $userObj->username;
                $contestRanklistObj[$i]->stu_id = $userObj->username;
            }
            else
            {
                $contestRanklistObj[$i]->nickname = $userInfoObj->nickname;
                $contestRanklistObj[$i]->realname = $userInfoObj->realname;
                $contestRanklistObj[$i]->stu_id = $userInfoObj->stu_id;
            }
            $contestRanklistObj[$i]->username = $userObj->username;

            $contestRanklistObj[$i]->penalty_list = json_decode($contestRanklistObj[$i]->penalty_list, true);
            $contestRanklistObj[$i]->result_list = json_decode($contestRanklistObj[$i]->result_list, true);
        }
        $data['problems'] = ContestProblem::where('contest_id', $contest_id)->get();
        $data['ranklist'] = $contestRanklistObj;
        $data['contest_id'] = $contest_id;
        $data['counter'] = 1;
        /** Cache the result for a better performance when multiple visit at the same time */
        if(Contest::where('contest_id', $contest_id)->first()->isEnded())
        {
            Cache::put("contest-$contest_id.ranklist.final", $data, Carbon::now()->addDay());
        }
        else
        {
            Cache::put("contest-$contest_id.ranklist.$timestamp", $data, Carbon::now()->addMinutes(1));
        }
        return View::make('contest.ranklist', $data);
    }

    /**
    *@uses contest.ranklist.php
     * @stub getContestRanklistByPageID
     */
    public function getContestRanklistByPageID(Request $request, $contest_id, $page_id)
    {
        $data = [];

        return View::make('contest.ranklist', $data);
    }

    /**
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

    /**
    *@uses status.list.php
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
            if($key == "pid")
            {
                $queryArr['pid'] = ContestProblem::select('problem_id')->where([
                    'contest_id' => $contest_id,
                    'contest_problem_id' => $val
                ])->first()->problem_id;
            }
        }
        $submissionObj = Submission::where($queryArr)->orderby('runid', 'desc')->skip(($page_id - 1) * $itemsPerPage)->take($itemsPerPage)->get();

        for ($count = 0; $count < $submissionObj->count(); $count++)
        {
            $data['submissions'][$count] = $submissionObj[$count];
            $tmpUserObj = User::where('uid', $submissionObj[$count]->uid)->first();
            $tmpProblemObj = ContestProblem::where([
                "contest_id" => $contest_id,
                "problem_id" => $submissionObj[$count]->pid
            ])->first();
            $problemTitle = $tmpProblemObj['problem_title'];
            $username = $tmpUserObj['username'];
            $data['submissions'][$count]->userName = $username;
            $data['submissions'][$count]->problemTitle = $problemTitle;
            $data['submissions'][$count]->contestProblemId = $tmpProblemObj['contest_problem_id'];
            $data['submissions'][$count]->nickname = $tmpUserObj->info->nickname;
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
        if(($page_id - 1) * $itemsPerPage >= $submissionObj->count())
        {
            $data['lastPage'] = 1;
        }
        Event::fire(new ContestPageVisited($contest_id));
        return View::make('status.list', $data);
    }

    /**
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
        $errMsg = new MessageBag;
        $contestObj = Contest::where('contest_id', $contest_id)->first();
        $contestProblemObj = ContestProblem::where('contest_id',$contest_id)->get();
        if($request->method() == "POST")
        {
            $input = $request->all();
            //Validation Check
            $vdtor = Validator::make($input, [
                'problem_id' => 'required | exists:problems'
            ]);
            if($vdtor->fails())
            {
                $errMsg = $vdtor->errors();
            }
            $beginTime = strtotime($input['begin_time']);
            $endTime = strtotime($input['end_time']);
            $registerBeginTime = strtotime($input['register_begin_time']);
            $registerEndTime = strtotime($input['register_end_time']);
            if(!isset($input['problem_id']))
            {
                $errMsg->add('err', "You must Provide at least one problem!");
                return Redirect::to("/dashboard/contest/$contest_id")->withErrors($errMsg)->withInput($input);
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
            if(!$errMsg->isEmpty())
            {
                //var_dump($errMsg);
                return Redirect::to("/dashboard/contest/$contest_id")->withErrors($errMsg)->withInput($input);
            }
            $contestObj = Contest::where('contest_id', $contest_id)->first();
            //var_dump($contestObj->primaryKey);
            $oldContent = Contest::where('contest_id', $contest_id)->first();
            $oldContentString = $oldContent["contest_id"] . $oldContent["contest_name"] . $oldContent["begin_time"] . $oldContent["end_time"] . $oldContent["register_begin_time"] . $oldContent["register_end_time"] . $oldContent["admin_id"] . $oldContent["contest_type"];
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
                $contestProblemObj->problem_color = $input['problem_color'][$i];
                $contestProblemObj->contest_problem_id = $i + 1;
                $contestProblemObj->save();

                $problemObj = Problem::where('problem_id', $contestProblemObj->problem_id);
                $problemObj->update([
                    "visibility_locks" => 1
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
            $uid = $request->session()->get('uid');
            $newContent = $contestObj;
            if (substr($newContent["end_time"], 10, 1) == 'T')
                $newContent["end_time"] = substr($newContent["end_time"], 0, 10) . ' ' . substr($newContent["end_time"], 11);
            $newContentString = $newContent["contest_id"] . $newContent["contest_name"] . $newContent["begin_time"] . $newContent["end_time"] . $newContent["register_begin_time"] . $newContent["register_end_time"] . $newContent["admin_id"] . $newContent["contest_type"];
            $oldContentMd5 = md5($oldContentString);
            $newContentMd5 = md5($newContentString);
            if ($oldContentMd5 != $newContentMd5)
                OJLog::editContest($uid, $contest_id, $oldContent, $newContent);
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

    /**
     * @function deleteContest
     * @input $request,$contest_id
     *
     * @return Redirect
     * @description deleteContest
     */
    public function deleteContest(Request $request, $contest_id)
    {
        $uid = $request->session()->get('uid');
        $contestObj = Contest::where('contest_id', $contest_id)->first();
        $deleteContent = $contestObj;
        OJLog::deleteContest($uid, $contest_id, $deleteContent);
        ContestUser::where('contest_id',$contest_id)->delete();
        ContestProblem::where('contest_id',$contest_id)->delete();
        Contest::where('contest_id',$contest_id)->delete();
        return Redirect::to("/dashboard/contest");
    }

    /**
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
            return Redirect::to("/contest/$contest_id");
        }
        return View::make('contest.register', $data);
    }

    /**
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
                $data[$count]["id"] = $contestBalloonEventObj->id;
                $data[$count]["username"] = User::where('uid', $submissionObj->uid)->first()->username;
                $data[$count]["contest_problem_id"] = $contestProblemObj->contest_problem_id;
                $data[$count]["short_name"] = $contestProblemObj->problem_title;
                $data[$count]["color"] = $contestProblemObj->problem_color;
                $data[$count]["nickname"] = User::find($submissionObj->uid)->info->nickname;
                if($contestBalloonEventObj->event_status == env('BALLOON_SEND',1))
                {
                    $data[$count]["event"] = 'send';
                }
                else
                {
                    $data[$count]["event"] = 'discard';
                }
                if($contestBalloonEventObj->send_status == env('BALLOON_DONE', 1))
                {
                    $data[$count]["status"] = 'Done';
                }
                else
                {
                    $data[$count]["status"] = 'Pending';
                }
                $count++;
            }
        }
        $data["count"] = $count;
        return json_encode($data);
    }

    /**
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

    /**
     * @function changeContestBalloonStatus
     * @input $request $contest_id $id
     *
     * @return Redirect
     * @description change Ballon Status
     */
    public function changeContestBalloonStatus(Request $request, $contest_id, $id)
    {
        $contestBalloonEventObj = ContestBalloonEvent::where('id', $id)->first();
        if($contestBalloonEventObj->send_status == 0)
            $contestBalloonEventObj->update(['send_status' => 1]);
        return Redirect::to("/contest/$contest_id/balloon");
    }

    /**
     * @function ContestRanklistExport
     * @input $request $contest_id
     *
     * @return excel file
     * @description export contest ranklist excel file
     */
    public function exportContestRanklist(Request $request, $contest_id)
    {
        $contestObj = Contest::where('contest_id', $contest_id)->first();
        if($contestObj->isEnded() && Cache::has("contest-$contest_id.ranklist.final"))
        {
            $data = Cache::get("contest-$contest_id.ranklist.final");
            if (current($data['users'])['attributes']['uid'] <= 0)
                return Redirect::to("/contest/$contest_id/ranklist");
            Excel::create
            (
                "ranklist_$contest_id",
                function ($excel) use($data)
                {
                    $excel->sheet('Ranklist',
                        function ($sheet) use ($data)
                        {
                            $problems = $data['problems'];
                            $titleRow = array('Rank', '学号', '真实姓名', 'Solve', 'Penalty');
                            foreach($problems as $contestProblemObj)
                            {
                                $titleRow[] = $contestProblemObj->problem_title;
                            }
                            $titleRow[] = "OJ总过题数";
                            $titleRow[] = "OJ总提交数";
                            $sheet->prependRow(1, $titleRow);
                            $sheet->setSize(array('A1' => array('width' => 10, 'height' => 20)));
                            $sheet->row(1, function($row)
                            {
                               $row->setBackground('#95a5a6');
                               $row->setFontColor('#ffffff');
                            });
                            $i = 1;
                            foreach($data['users'] as $user)
                            {
                                if($user->uid == 0)
                                    continue;
                                $row = array
                                (
                                    $i++,
                                    $user->info->stu_id,
                                    $user->info->realname,
                                    $user->infoObj->totalAC,
                                );
                                $totalPenalty = "";
                                $totalHour = intval($user->infoObj->totalPenalty / 60 / 60);
                                $totalPenalty = $totalHour <= 9 ? "0$totalHour" : "$totalHour";
                                $totalPenalty = $totalPenalty . ":" . substr(strval($user->infoObj->totalPenalty % 3600 / 60 + 100), 1, 2);
                                $totalPenalty = $totalPenalty . ":" . substr(strval($user->infoObj->totalPenalty % 60 + 100), 1, 2);
                                $row[] = $totalPenalty;
                                foreach($problems as $problem)
                                {
                                    $problem_result = "";
                                    if (isset($user->infoObj->result[$problem->contest_problem_id]))
                                    {
                                        $result = $user->infoObj->result[$problem->contest_problem_id];
                                        if($result == "Rejudging" || $result == "Pending")
                                        {
                                            $problem_result = "Pending/Rejudging";
                                        }
                                        elseif($result != 'Accepted' && $result != 'First Blood')
                                        {
                                            $problem_result = "(" . strval($user->infoObj->penalty[$problem->contest_problem_id]) . ")";
                                            $sheet->cell
                                            (
                                                strval(chr(ord('A') + count($row))) . strval($i),
                                                function($cell)
                                                {
                                                    $cell->setBackground('#c9302c');
                                                }
                                            );
                                        }
                                        else
                                        {
                                            $problemPenalty = "";
                                            $accepted_time = $user->infoObj->time[$problem->contest_problem_id];
                                            $hour = intval($accepted_time / 60 / 60);
                                            $problemPenalty = $hour <= 9 ? "0$hour" : "$hour";
                                            $problemPenalty = $problemPenalty . ":" . substr(strval($accepted_time % 3600 / 60 + 100), 1, 2);
                                            $problemPenalty = $problemPenalty . ":" . substr(strval($accepted_time % 60 + 100), 1, 2);
                                            $problem_result = $problemPenalty;
                                            if($result == "First Blood")
                                            {
                                                $sheet->cell
                                                (
                                                    strval(chr(ord('A') + count($row))) . strval($i),
                                                    function ($cell)
                                                    {
                                                        $cell->setBackground('#337ab7');
                                                    }
                                                );
                                            }
                                            else
                                            {
                                                $sheet->cell
                                                (
                                                    strval(chr(ord('A') + count($row))) . strval($i),
                                                    function ($cell)
                                                    {
                                                        $cell->setBackground('#5cb85c');
                                                    }
                                                );
                                            }
                                        }
                                    }
                                    $row[] = $problem_result;
                                }
                                $row[] = Userinfo::where('uid', $user->uid)->first()->ac_count;
                                $row[] = Userinfo::where('uid', $user->uid)->first()->submit_count;
                                $sheet->row($i, $row);
                            }
                            /** end for*/
                        });
                        /** end sheet */
                }
            )->download('xls');
        }
        return Redirect::to("/contest/$contest_id/ranklist");
    }

    public function exportContestRanklist_new(Request $request, $contest_id)
    {
        $contestObj = Contest::where('contest_id', $contest_id)->first();
        if($contestObj->isEnded() && Cache::has("contest-$contest_id.ranklist.final"))
        {
            $data = Cache::get("contest-$contest_id.ranklist.final");
            if (count($data['ranklist']) <= 0)
                return Redirect::to("/contest/$contest_id/ranklist");
            Excel::create
            (
                "ranklist_$contest_id",
                function ($excel) use($data)
                {
                    $excel->sheet('Ranklist',
                        function ($sheet) use ($data)
                        {
                            $problems = $data['problems'];
                            $titleRow = array('Rank', '学号', '真实姓名', 'Solve', 'Penalty');
                            foreach($problems as $contestProblemObj)
                            {
                                $titleRow[] = $contestProblemObj->problem_title;
                            }
                            $titleRow[] = "OJ总过题数";
                            $titleRow[] = "OJ总提交数";
                            $sheet->prependRow(1, $titleRow);
                            $sheet->setSize(array('A1' => array('width' => 10, 'height' => 20)));
                            $sheet->row(1, function($row)
                            {
                               $row->setBackground('#95a5a6');
                               $row->setFontColor('#ffffff');
                            });
                            $i = 1;
                            foreach($data['ranklist'] as $user)
                            {
                                if($user->uid == 0)
                                    continue;
                                $row = array
                                (
                                    $i++,
                                    $user->stu_id,
                                    $user->realname,
                                    $user->total_ac,
                                );
                                $row[] = $user->total_penalty;
                                foreach($problems as $problem)
                                {
                                    $problem_result = "";
                                    if(isset($user->result_list[$problem->contest_problem_id]))
                                    {
                                        $result = $user->result_list[$problem->contest_problem_id];
                                        if($result == "Rejudging" || $result == "Pending")
                                        {
                                            $problem_result = "Pending/Rejudging";
                                        }
                                        elseif($result != 'Accepted' && $result != 'First Blood')
                                        {
                                            $problem_result = "(" . strval($user->penalty_list[$problem->contest_problem_id]['penalty']) . ")";
                                            $sheet->cell
                                            (
                                                strval(chr(ord('A') + count($row))) . strval($i),
                                                function($cell)
                                                {
                                                    $cell->setBackground('#c9302c');
                                                }
                                            );
                                        }
                                        else
                                        {
                                            $problem_result = $user->penalty_list[$problem->contest_problem_id]['time'];
                                            if($result == "First Blood")
                                            {
                                                $sheet->cell
                                                (
                                                    strval(chr(ord('A') + count($row))) . strval($i),
                                                    function ($cell)
                                                    {
                                                        $cell->setBackground('#337ab7');
                                                    }
                                                );
                                            }
                                            else
                                            {
                                                $sheet->cell
                                                (
                                                    strval(chr(ord('A') + count($row))) . strval($i),
                                                    function ($cell)
                                                    {
                                                        $cell->setBackground('#5cb85c');
                                                    }
                                                );
                                            }
                                        }
                                    }
                                    $row[] = $problem_result;
                                }
                                $row[] = Userinfo::where('uid', $user->uid)->first()->ac_count;
                                $row[] = Userinfo::where('uid', $user->uid)->first()->submit_count;
                                $sheet->row($i, $row);
                            }
                            /** end for*/
                        });
                        /** end sheet */
                }
            )->download('xls');
        }
        return Redirect::to("/contest/$contest_id/ranklist");
    }

    /*
     * @function postMemberList
     * @input $request
     *
     * @return Text for succeed, NULL for error
     * @description: AJAX Interface, post to /ajax/memberlist then
     * will response with raw text containing all the
     * username with comma seperated
     */
    public function postMemberList(Request $request)
    {
        $input = $request->input();
        $file = $request->file('memberlist');

        if(!isset($input['file_type']) || !isset($input['selected_col']))
            return NULL;

        if(!$file->isValid())
            return NULL;
        /*
         *  Current only support read from
         *  Excel file
         */

        if($input['file_type'] == 'xls')
        {
            $fileName = $file->getPathname();
            $xlsObj = Excel::load($fileName, function($reader){

            });

            $dataArr = $xlsObj->toArray();

            foreach($dataArr as $row)
            {
                if(!isset($row[$input['selected_col']]))
                    return NULL;

                echo $row[$input['selected_col']] . ',';
            }
        }
    }

    public function getRunningContestsJson(Request $request)
    {
        $data = [];
        $curTime = time();
        $contestObj = Contest::all();
        $i = 0;
        $data['contests'] = [];
        foreach($contestObj as $contest)
        {
            if($curTime >= strtotime($contest->begin_time) && $curTime <= strtotime($contest->end_time))
            {
                $data['contests'][$i++] = $contest;
            }
        }
        return response()->json($data);
    }

    /**
     * @function getRandStr
     * @param $len
     * @return string
     * @description get random length string
     */
    public function getRandStr($len)
    {
        $chars = array(
            "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
            "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
            "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
            "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
            "S", "T", "U", "V", "W", "X", "Y", "Z"
        );
        $charsLen = count($chars) - 1;
        shuffle($chars);
        $output = "";
        for ($i = 0; $i < $len; $i++) {
            $output .= $chars[mt_rand(0, $charsLen)];
        }
        return $output;
    }

    /**
     * @function newContestRandomUsers
     * @param $contest_id, $school, $count
     * @return json
     * @description creat random users for contest
     */
    public function newContestRandomUsers($contest_id, $school, $count)
    {
        $contestObj = Contest::where('contest_id', $contest_id)->first();
        $contestRandomUser = array();
        $contestRandomUserCount = $count;
        $prefix = $contestObj->contest_name . "_" . $school . "_";
        while (count($contestRandomUser) < $contestRandomUserCount) {
            $contestRandomUser[$prefix . str_pad(count($contestRandomUser) + 1, 3, "0", STR_PAD_LEFT)] = $this->getRandStr(8);
        }
        foreach ($contestRandomUser as $key => $value) {
            $userObject = User::where('email', $key . "@contest.private")->first();
            if (!isset($userObject)) {
                $userObject = new User;
                $userObject->username = $key;
                $userObject->password = Hash::make($value);
                $userObject->email = $key . "@contest.private";
                $userObject->registration_time = date('Y-m-d h:i:s');
                $userObject->save();
            } else {
                $contestRandomUserStatus['error'] = "Users existed!";
                return json_encode($contestRandomUserStatus);
            }
            $userinfoObject = Userinfo::where('uid', $userObject->uid)->first();
            if (!isset($userinfoObject)) {
                $userinfoObject = new Userinfo;
                $userinfoObject->uid = $userObject->uid;
                $userinfoObject->nickname = $key;
                $userinfoObject->realname = $key;
                $userinfoObject->school = $school;
                $userinfoObject->stu_id = 00000000;
                $userinfoObject->save();
            }
        }
        return json_encode($contestRandomUser);
    }

    /**
     * @function deleteContestRandomUsers
     * @param  $contest_id
     * @return json
     * @description delete random users
     */
    public function deleteContestRandomUsers($contest_id)
    {
        $contestObj = Contest::where('contest_id', $contest_id)->first();
        $userAll = User::all();
        $data['status'] = "fail";
        if ($userAll != NULL) {
            foreach ($userAll as $userObject) {
                $username = $userObject->username;
                if (substr($username, 0, strlen($contestObj->contest_name)) == $contestObj->contest_name) {
                    User::where('uid', $userObject->uid)->delete();
                    Userinfo::where('uid', $userObject->uid)->delete();
                    $data['status'] = "success";
                }
            }
            return $data;
        }
    }
}


