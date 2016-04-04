<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
        if($request->method() == "POST")
        {
            $input = $request->all();
            $trainingObj = new Train;
            $trainingObj->train_name = $input['train_name'];
            $trainingObj->train_chepter = $input['train_chepter'];
            $trainingObj->description = " ";
            $trainingObj->train_type = 0;
            $trainingObj->auth_id = $request->session()->get('uid');
            $trainingObj->save();
            var_dump($trainingObj->train_id);
            for($i = 0; $i < count($input['problem_id']); $i++)
            {
                $trainingProblemObj = new TrainProblem;
                $trainingProblemObj->train_id = $trainingObj->train_id;
                $trainingProblemObj->problem_id = $input['problem_id'][$i];
                $trainingProblemObj->chepter_id = $input['problem_chepter'][$i];
                $trainingProblemObj->train_problem_id = count(TrainProblem::where('chepter_id', $input['problem_chepter'][$i])->get())+1;
                $trainingProblemObj->problem_title = $input['problem_name'][$i];
                $trainingProblemObj->problem_level = 0;
                $trainingProblemObj->save();
            }
            return Redirect::to('/dashboard/training/p/1');
        }
        return View::make("training.add");
    }

    public function deleteTraining(Requset $request, $train_id)
    {
        Train::where('train_id', $train_id)->delete();
        TrainProblem::where('train_id', $train_id)->delete();
        return Redirect::to('/dashboard/training/p/1');
    }

    public function setTraining(Request $request)
    {

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
        $data['chepterac'] = [];
        $uid = $request->session()->get('uid');
        $trainingObj = Train::where('train_id', $train_id)->first();
        $data['training'] = $trainingObj;
        //chepter_in is to find which chepter is user in
        $chepter_in = 1 ;
        for($i = 1; $i <= $trainingObj->train_chepter; $i++)
        {
            $trainingProblemObj = TrainProblem::where([
                'train_id' => $train_id,
                'chepter_id' => $i
            ])->get();
            //checkChepterAc is to find whether user has finished this chepter
            $checkChepterAc = 0;
            $problem_num = 0;
            foreach($trainingProblemObj as $trainingProblem)
            {
                $submissionObj = Submission::where([
                    'uid' => $uid,
                    'pid' => $trainingProblem->problem_id,
                    'result' => 'Accepted'
                ])->orderby('runid','asc')->first();
                if(!isset($submissionObj))
                    $checkChepterAc = 0;
                else
                    $checkChepterAc = 1;
                $data['chepter'][$i][$problem_num] = $trainingProblem->problem;
                $data['chepter'][$i][$problem_num++]['ac'] = $checkChepterAc;
            }
            if($checkChepterAc == 1)
                $chepter_in = $i+1;
        }
        $data['chepter_in'] = $chepter_in;
        return View::make('training.index')->with($data);
    }
}
