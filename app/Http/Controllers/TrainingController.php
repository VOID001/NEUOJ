<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

use App\Train;
use App\TrainProblem;
use Problem;
use App\Submission;
use App\User;
use App\Userinfo;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Controller;

class TrainingController extends Controller
{
    public function showTrainingDashboard(Request $request)
    {
        $data = [];
        $data['training'] = [];
        $trainingObj = Train::orderby('train_id', 'asc')->get();
        $data['training'] = $trainingObj;
        $trainNum = $trainingObj->count();
        $data['trainNum'] = $trainNum;
        return View::make('training.dashboard')->with($data);
    }

    public function addTraining(Request $request)
    {
        $data = [];
        $errMsg = new MessageBag;
        if($request->method() == "POST")
        {
            $this->validate($request, [
                'train_name' => 'required | unique:train_info',
                'problem_id' => 'required | exists:problems',
                'description' => 'required'
            ]);
            $input = $request->all();
            $trainingObj = new Train;
            $trainingObj->train_name = $input['train_name'];
            $trainingObj->train_chapter = $input['train_chapter'];
            $trainingObj->description = $input['description'];
            $trainingObj->train_type = 0;
            $trainingObj->auth_id = $request->session()->get('uid');
            $trainingObj->save();
            for($i = 0; $i < count($input['problem_id']); $i++)
            {
                $trainingProblemObj = new TrainProblem;
                $trainingProblemObj->train_id = $trainingObj->train_id;
                $trainingProblemObj->problem_id = $input['problem_id'][$i];
                $trainingProblemObj->chapter_id = $input['problem_chapter'][$i];
                $trainingProblemObj->train_problem_id = $i + 1;
                $trainingProblemObj->problem_title = $input['problem_name'][$i];
                $trainingProblemObj->problem_level = 0;
                $trainingProblemObj->save();
            }
            $train_id = $trainingObj->train_id;
            return Redirect::to("/dashboard/training/$train_id");
        }
        return View::make("training.add");
    }

    public function deleteTraining(Request $request, $train_id)
    {
        Train::where('train_id', $train_id)->delete();
        TrainProblem::where('train_id', $train_id)->delete();
        return Redirect::to('/dashboard/training/p/1');
    }

    public function setTraining(Request $request, $train_id)
    {
        $data = [];
        $errMsg = new MessageBag;
        $trainingObj = Train::where('train_id', $train_id)->first();
        $trainingProblemObj = TrainProblem::where('train_id', $train_id)->get();
        if($request->method() == "POST")
        {
            $this->validate($request, [
                'train_name' => 'required',
                'problem_id' => 'required | exists:problems',
                'description' => 'required'
            ]);
            $input = $request->all();
            $trainingObj->train_name = $input['train_name'];
            $trainingObj->train_chapter = $input['train_chapter'];
            $trainingObj->description = $input['description'];
            $trainingObj->save();
            TrainProblem::where('train_id', $train_id)->delete();
            for($i = 0; $i < count($input['problem_id']); $i++)
            {
                $trainingProblemObj = new TrainProblem;
                $trainingProblemObj->train_id = $trainingObj->train_id;
                $trainingProblemObj->problem_id = $input['problem_id'][$i];
                $trainingProblemObj->chapter_id = $input['problem_chapter'][$i];
                $trainingProblemObj->train_problem_id = $i + 1;
                $trainingProblemObj->problem_title = $input['problem_name'][$i];
                $trainingProblemObj->problem_level = 0;
                $trainingProblemObj->save();
            }
            return Redirect::to("/dashboard/training/$train_id");
        }
        $data['train_info'] = $trainingObj;
        $data['train_problem'] = $trainingProblemObj;
        $data['train_problem_count'] = count($trainingProblemObj);
        return View::make("training.set")->with($data);
    }

    public function getTrainingList(Request $request)
    {
        $data = [];
        $data['training'] = [];
        $trainingObj = Train::orderby('train_id', 'asc')->get();
        $trainNum = $trainingObj->count();
        $data['training'] = $trainingObj;
        $data['trainNum'] = $trainNum;
        return View::make('training.list')->with($data);
    }

    public function getTrainingByID(Request $request, $train_id)
    {
        $data = [];
        $data['chapter'] = [];
        $uid = $request->session()->get('uid');
        $trainingObj = Train::where('train_id', $train_id)->first();
        $data['training'] = $trainingObj;
        /* chapter_in is to find which chapter is user in */
        $chapter_in = 1 ;
        for($i = 1; $i <= $trainingObj->train_chapter; $i++)
        {
            $trainingProblemObj = TrainProblem::where([
                'train_id' => $train_id,
                'chapter_id' => $i
            ])->get();
            /* checkChapterAc is to find whether user has finished this chapter */
            if(isset($trainingProblemObj) && $chapter_in == $i)
                $checkChapterAc = 1;
            $problem_num = 0;
            foreach($trainingProblemObj as $trainingProblem)
            {
                if(!$trainingProblem->problem->getNumberOfUsedContests())
                {
                    $submissionObj = Submission::where([
                        'uid' => $uid,
                        'pid' => $trainingProblem->problem_id,
                        'result' => 'Accepted'
                    ])->orderby('runid','asc')->first();
                    if(isset($submissionObj) && $checkChapterAc == 1)
                        $checkChapterAc = 1;
                    else
                        $checkChapterAc = 0;
                    $data['chapter'][$i][$problem_num] = $trainingProblem;
                    if($trainingProblem->problem_title == "")
                        $data['chapter'][$i][$problem_num]['title'] = $trainingProblem->problem->title;
                    else
                        $data['chapter'][$i][$problem_num]['title'] = $trainingProblem->problem_title;
                    $data['chapter'][$i][$problem_num++]['ac'] = isset($submissionObj) ? 1 : 0;
                }
            }
            if($checkChapterAc == 1)
                $chapter_in = $i + 1;
        }
        if(RoleController::is('admin'))
            $chapter_in = $trainingObj->train_chapter+1;
        $data['chapter_in'] = $chapter_in;
        return View::make('training.index')->with($data);
    }

    public function getTrainingRanklistByPageID(Request $request, $train_id, $page_id)
    {
        $data = [];
        $trainUserList = [];
        $uid = $request->session()->get('uid');
        $trainingObj = Train::where('train_id', $train_id)->first();
        $trainingProblemObj = TrainProblem::where('train_id', $train_id)->where('chapter_id', 1)->get();
        $user_num = 0;
        foreach($trainingProblemObj as $train_problem)
        {
            $problemAcList = Submission::select('uid')->where('pid', $train_problem->problem_id)->where('result', 'Accepted')->get()->unique('uid');
            foreach($problemAcList as $user)
            {
                $user_chapter = $trainingObj->getUserChapter($user->uid) - 1;
                if($user_chapter == 0)
                    continue;
                $trainUserList[$user_num] = $user;
                $trainUserList[$user_num]['chapter'] = $user_chapter;
                $user_num++;
            }
        }
        $trainUserList = collect($trainUserList)->unique('uid');
        foreach($trainUserList as &$trainUser)
        {
            $trainUser['nickname'] = Userinfo::select('nickname')->where('uid', $trainUser->uid)->first()->nickname;
            if($trainUser['chapter'] == 0)
                $trainUser['submit_time'] = 0;
            else
            {
                $userChapterProblemList = TrainProblem::where('train_id', $train_id)->where('chapter_id', $trainUser['chapter'])->get();
                $time = "";
                foreach($userChapterProblemList as $problem)
                {
                    $ac_time = Submission::select('submit_time')->where([
                        'pid' => $problem->problem_id,
                        'uid' => $trainUser->uid,
                        'result' => 'Accepted',
                    ])->first();
                    if($ac_time->submit_time > $time)
                        $time = $ac_time->submit_time;
                }
                $trainUser['submit_time'] = $time;
            }
        }
        $trainUserList = $trainUserList->all();
        usort($trainUserList, [$this, 'cmp']);
        $userPerPage = 30;
        if($userPerPage * ($page_id - 1) > count($trainUserList))
            return Redirect::to("/training/$train_id");
        for($i = $userPerPage * ($page_id - 1); $i < ($userPerPage * $page_id > count($trainUserList) ? count($trainUserList) : $userPerPage * $page_id); $i++)
            $data['ranklist'][$i] = $trainUserList[$i];
        $data['counter'] = 1;
        $data['train_id'] = $trainingObj->train_id;
        $data['train_name'] = $trainingObj->train_name;
        $data['page_num'] = ceil(count($trainUserList) / $userPerPage);
        $data['page_id'] = $page_id;
        $data['page_user'] = $userPerPage;
        return View::make('training.ranklist')->with($data);
    }

    public function cmp($userA, $userB)
    {
        if($userA['chapter'] == $userB['chapter'])
        {
            return $userA['submit_time'] > $userB['submit_time'];
        }
        return $userA['chapter'] < $userB['chapter'];
    }
}
