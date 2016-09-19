<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

use App\User;
use App\Problem;
use App\Submission;
use App\Testcase;
use Mockery\Exception;
use Storage;
use App\ContestProblem;
use App\ContestUser;
use App\Contest;
use App\Train;
use App\TrainProblem;
use Symfony\Component\VarDumper\Caster\ExceptionCaster;

class ProblemController extends Controller
{
    public function getProblem(Request $request)
    {
        $page_id = $request->session()->get('page_id');
        if($page_id == NULL) $page_id = 1;
        return Redirect::to("/problem/p/".$page_id);
    }

    public function getProblemByID(Request $request, $problem_id)
    {
        $problemObj = Problem::where("problem_id", $problem_id)->first();
        if($problemObj == NULL)
        {
            abort(404);
        }
        $roleController = new RoleController();
        if(!$roleController->is("admin") && $problemObj->visibility_locks != 0)
        {
            abort(404);
        }
        $jsonObj = json_decode($problemObj->description);
        $data['problem'] = $problemObj;
        if($jsonObj != NULL)
        {
            foreach ($jsonObj as $key => $val)
            {
                $data['problem']->$key = $val;
            }
        }
        $data['problem']->totalSubmissionCount = Submission::getValidSubmissionCount(0, $problem_id);
        $data['problem']->acSubmissionCount = Submission::where([
            'pid' => $problem_id,
            'result' => "Accepted"
        ])->get()->unique('uid')->count();
        return View::make("problem.index", $data);
    }

    public function getProblemListByPageID(Request $request, $page_id)
    {
        $problemPerPage = 20;

        /**  Remove the customize pagination function
        if($request->method() == "GET")
        {
            if(($problemPerPage = $request->session()->get('problem_per_page')) == NULL)
                $problemPerPage = 10;
        }
        elseif($request->method() == "POST")
        {
            $input = $request->input();
            if(($problemPerPage = $input['problem_per_page']) == NULL)
                $problemPerPage = 10;
            else
                $request->session()->put('problem_per_page', $problemPerPage);
        }
         **/
        $data = [];
        $data = Problem::getProblemItemsInPage($problemPerPage, $page_id);
        $request->session()->put('page_id', $page_id);
        return View::make('problem.list', $data);
    }

    public function showProblemDashboard(Request $request)
    {
        return Redirect::to('/dashboard/problem/p/1');
    }

    public function showProblemDashboardByPageID(Request $request, $page_id)
    {
        $problemPerPage = 20;
        $data = [];

        $data = Problem::getProblemItemsInPage($problemPerPage, $page_id);
        if(session('status'))
        {
            $data['status'] = session('status');
        }

        return View::make('problem.manage', $data);
    }

    public function setProblem(Request $request, $problem_id)
    {
        $data[] = NULL;
        $data['infos'] = [];
        $data['errors'] = [];
        $problemObj = Problem::where('problem_id', $problem_id)->first();
        $testcaseObj = Testcase::where('pid', $problem_id)->first();
        if($problemObj == NULL)
        {
            return Redirect::to('/dashboard/problem/');
        }
        if($testcaseObj != NULL)
        {
            $testcaseObj = Testcase::where('pid', $problem_id)->get();
        }
        $data['testcases'] = $testcaseObj;
        if($request->method() == "POST")
        {
            $this->validate($request, [
                "title" => "required",
                "input_file" => "required",
                "output_file" => "required",
            ]);
            /*
             * POST means update , Update the problem Info
             */
            $updateProblemData = $request->input();

            /*
             * Description is stored in json format
             * encode it and store it
             * And do not store limitation info in the description
             */
            unset($updateProblemData['_token']);
            foreach($updateProblemData as $key => $val)
            {
                if(strpos($key, "limit"))
                {
                    continue;
                }
                $jsonObj[$key] = $val;
            }
            unset($updateProblemData['input']);
            unset($updateProblemData['output']);
            unset($updateProblemData['sample_input']);
            unset($updateProblemData['sample_output']);
            unset($updateProblemData['source']);
            unset($updateProblemData['deleted']);
            unset($updateProblemData['score']);
            unset($updateProblemData['rank']);
            $updateProblemData['description'] = json_encode($jsonObj);
            Problem::where('problem_id', $problem_id)->update($updateProblemData);
            /*
             * Check if testcase files are changed
             */
            $uploadInput = $request->file('input_file');
            $uploadOutput = $request->file('output_file');
            $deleteList = $request->input('deleted');
            $rankList = $request->input('rank');    //to show problem's rank
            $scoreList = $request->input('score');
            for($i = 0; $i < count($deleteList); $i++)
            {
                Testcase::where([
                    'pid' => $problem_id,
                    'rank' => $deleteList[$i],
                ])->delete();
            }
            if(count($uploadInput) == count($uploadOutput))
            {
                $testcasenum = Testcase::where('pid', $problem_id)->count();
                for($i = 0; $i < count($uploadInput); $i++)
                {
                    if($i < $testcasenum)
                    {
                        Testcase::where([
                            'pid' => $problem_id,
                            'rank' => $rankList[$i],
                        ])->update(['score' => $scoreList[$i]]);
                        if(!$uploadInput[$i] && !$uploadOutput[$i])
                        {
                            continue;
                        }
                        if($uploadInput[$i])
                        {
                            $updateTestcaseData['input_file_name'] = $problem_id . "-" . time() . "-" . $uploadInput[$i]->getClientOriginalName();
                            $inputContent = file_get_contents($uploadInput[$i]->getRealPath());
                            $updateTestcaseData['md5sum_input'] = md5($inputContent);
                            Storage::put(
                                'testdata/'. $updateTestcaseData['input_file_name'],
                                $inputContent
                            );
                        }
                        if($uploadOutput[$i])
                        {
                            $updateTestcaseData['output_file_name'] = $problem_id . "-". time() . "-" . $uploadOutput[$i]->getClientOriginalName();
                            $outputContent = file_get_contents($uploadOutput[$i]->getRealPath());
                            $updateTestcaseData['md5sum_output'] = md5($outputContent);
                            Storage::put(
                                'testdata/'. $updateTestcaseData['output_file_name'],
                                $outputContent
                            );
                        }
                        Testcase::where([
                            'pid' => $problem_id,
                            'rank' => $rankList[$i],
                        ])->update($updateTestcaseData);
                        continue;
                    }
                    else if(!$uploadInput[$i] || !$uploadOutput[$i])
                    {
                        array_push($data['errors'], "Please upload correct files!");
                        break;
                    }
                    $updateTestcaseData['rank'] = $i + 1 + $testcasenum;
                    $updateTestcaseData['input_file_name'] = $problem_id . "-" . time() . "-" . $uploadInput[$i]->getClientOriginalName();
                    $updateTestcaseData['output_file_name'] = $problem_id . "-". time() . "-" . $uploadOutput[$i]->getClientOriginalName();
                    $updateTestcaseData['pid'] = $problem_id;
                    $updateTestcaseData['score'] = $scoreList[$i];
                    if($uploadInput[$i]->isValid() && $uploadOutput[$i]->isValid())
                    {
                        var_dump($updateTestcaseData);
                        $inputContent = file_get_contents($uploadInput[$i]->getRealPath());
                        $outputContent = file_get_contents($uploadOutput[$i]->getRealPath());
                        $updateTestcaseData['md5sum_input'] = md5($inputContent);
                        $updateTestcaseData['md5sum_output'] = md5($outputContent);
                        Storage::put(
                            'testdata/'. $updateTestcaseData['input_file_name'],
                            $inputContent
                        );
                        Storage::put(
                            'testdata/'. $updateTestcaseData['output_file_name'],
                            $outputContent
                        );
                        Testcase::create($updateTestcaseData);
                    }
                    else
                    {
                        array_push($data['errors'], "File Corrupted During Upload");
                        break;
                    }
                }
                array_push($data['infos'], "Update Testcase Data!");
            }
            array_push($data['infos'], "Update Problem Info!");
            $testcaseObj = Testcase::where('pid', $problem_id)->get();
            $i = 1;
            foreach($testcaseObj as $testCase)
            {
                Testcase::where([
                    'pid' => $problem_id,
                    'testcase_id' => $testCase->testcase_id
                ])->update(['rank' => $i]);
                $i++;
            }
            $testcaseObj = Testcase::where('pid', $problem_id)->get();
            $data['testcases'] = $testcaseObj;
            // Flash the status info into session
            return Redirect::to($request->server('REQUEST_URI'))->with('status', $data);
        }
        else
        {
            $status = session('status');
            /*
             * Previously we save the changes to the problem
             */
            if($status)
            {
                foreach($status as $key => $val)
                {
                    $data[$key] = $val;
                }
            }
            $jsonObj = json_decode($problemObj->description);
            $data['problem'] = $problemObj;
            if($jsonObj != NULL)
            {
                foreach ($jsonObj as $key => $val)
                {
                    $data['problem']->$key = $val;
                }
            }
            else
            {
                $data['problem'] = $problemObj;
            }
            return View::make('problem.set', $data);
        }
    }

    public function delProblem(Request $request, $problem_id)
    {
        Problem::where('problem_id', $problem_id)->delete();
        Testcase::where('pid', $problem_id)->delete();
        $status = "Successfully Delete Problem $problem_id";
        return Redirect::to('/dashboard/problem/')->with('status', $status);
    }

    public function addProblem(Request $request)
    {
        $data[] = NULL;
        $data['infos'] = [];
        $data['errors'] = [];
        $data['testcases'] = [];
        if($request->method() == "POST")
        {
            /*
             * POST means add , Add the problem Info
             */

            /* We need to validate some input */
            $this->validate($request, [
                "title" => "required",
                "input_file[]" => "required",
                "output_file[]" => "required",
            ]);

            $problemObj = new Problem();
            $testcaseObj = new Testcase();
            $problemObj->author_id = $request->session()->get('uid');
            $problemObj->save();
            $problem_id=Problem::max('problem_id');
            $data['testcases'] = $testcaseObj;
            $updateProblemData = $request->input();
            /*
             * Description is stored in json format
             * encode it and store it
             * And do not store limitation info in the description
             */
            unset($updateProblemData['_token']);
            foreach($updateProblemData as $key => $val)
            {
                if(strpos($key, "limit"))
                {
                    continue;
                }
                $jsonObj[$key] = $val;
            }
            unset($updateProblemData['input']);
            unset($updateProblemData['output']);
            unset($updateProblemData['sample_input']);
            unset($updateProblemData['sample_output']);
            unset($updateProblemData['source']);
            unset($updateProblemData['score']);
            $updateProblemData['description'] = json_encode($jsonObj);
            Problem::where('problem_id', $problem_id)->update($updateProblemData);
            /*
             * Check if testcase files are changed
             */
            $uploadInput = $request->file('input_file');
            $uploadOutput = $request->file('output_file');
            $scoreList = $request->input('score');
            $max_output_size =  $request->file('input_file')[0]->getClientSize() / 1000 + 4096;
            Problem::where('problem_id', $problem_id)->update([
                'output_limit' => $max_output_size
            ]);
            if(count($uploadInput) == count($uploadOutput))
            {
                Testcase::where('pid', $problem_id)->delete();
                for($i = 0; $i < count($uploadInput); $i++)
                {
                    if(!$uploadInput[$i] || !$uploadOutput[$i])
                    {
                        array_push($data['errors'], "Please upload correct files!");
                        break;
                    }
                    $updateTestcaseData['rank'] = $i + 1;
                    $updateTestcaseData['input_file_name'] = $problem_id . "-" . time() . "-" . $uploadInput[$i]->getClientOriginalName() . '-' . ($i+1) . '-in';
                    $updateTestcaseData['output_file_name'] = $problem_id . "-". time() . "-" . $uploadOutput[$i]->getClientOriginalName() . '-' . ($i+1) . '-out';
                    $updateTestcaseData['pid'] = $problem_id;
                    $updateTestcaseData['score'] = $scoreList[$i];
                    if($uploadInput[$i]->isValid() && $uploadOutput[$i]->isValid())
                    {
                        var_dump($updateTestcaseData);
                        $inputContent = file_get_contents($uploadInput[$i]->getRealPath());
                        $outputContent = file_get_contents($uploadOutput[$i]->getRealPath());
                        $updateTestcaseData['md5sum_input'] = md5($inputContent);
                        $updateTestcaseData['md5sum_output'] = md5($outputContent);
                        Storage::put(
                            'testdata/'. $updateTestcaseData['input_file_name'],
                            $inputContent
                        );
                        Storage::put(
                            'testdata/'. $updateTestcaseData['output_file_name'],
                            $outputContent
                        );
                        Testcase::create($updateTestcaseData);
                    }
                    else
                    {
                        array_push($data['errors'], "File Corrupted During Upload");
                        break;
                    }
                }
                array_push($data['infos'], "Update Testcase Data!");
            }
            array_push($data['infos'], "Update Problem Info!");
            // Flash the status info into session
            return Redirect::route('dashboard.problem')->with('status', $data);
        }
        else
        {
            $status = session('status');
            if($status)
            {
                foreach($status as $key => $val)
                {
                    $data[$key] = $val;
                }
            }
            $errors = session('errors');
            $data['errors'] = $errors;
            return View::make('problem.add',$data);
        }
    }

    public function getContestProblemByContestProblemID(Request $request, $contest_id, $problem_id)
    {
        $data = [];
        $uid = $request->session()->get('uid');
        $contestObj = Contest::where('contest_id', $contest_id)->first();
	$userObj = User::where('uid', $uid)->first();
	if($userObj)
            $username = $userObj->username;
        else
	    $username = "";
        if(time() < strtotime($contestObj->begin_time))
        {
            //Admin Special Privilege
            if(!($request->session()->get('uid') && $request->session()->get('uid') <= 2))
                return Redirect::to("/contest/$contest_id");
        }
        if($contestObj->contest_type == 1)
        {
            //if(!($request->session()->get('uid') && $request->session()->get('uid') <= 2))
            if(!RoleController::is('admin'))
            {
                $contestUserObj = ContestUser::where('username', $username)->first();
                //var_dump($contestUserObj);
                if($contestUserObj == NULL)
                    return Redirect::to('/contest/p/1');
            }
        }
        $contestProblemObj = ContestProblem::where([
            "contest_id" => $contest_id,
            "contest_problem_id" => $problem_id,
        ])->first();

        $realProblemID = $contestProblemObj->problem_id;

        $problemObj = Problem::where('problem_id', $realProblemID)->first();

        if($problemObj == NULL)
        {

        }
        $jsonObj = json_decode($problemObj->description);
        $data['problem'] = $problemObj;
        $data['problem']->problem_id = $problem_id;
        if($jsonObj != NULL)
        {
            foreach ($jsonObj as $key => $val)
            {
                $data['problem']->$key = $val;
            }
        }
        //var_dump($data['problem']);
        $data['problem']->title = $contestProblemObj->problem_title;
        $data['isContest'] = true;
        $data['contest'] = $contestObj;
        $data['problem']->acSubmissionCount = Submission::where([
            'pid' => $realProblemID,
            'cid' => $contest_id,
            'result' => "Accepted"
        ])->get()->unique('uid')->count();
        $data['problem']->totalSubmissionCount = Submission::getValidSubmissionCount($contest_id, $realProblemID);
        $data['problem']->realProblemID = $realProblemID;
        return View::make("problem.index", $data);
    }

    /*
     * @function importProblem
     * @input $request
     *
     * @return Redirect
     * @description import problem from given xml file
     *              if no file selected redirect back with err
     *              else parse xml and add data into database
     *              and storage
     */
    public function importProblem(Request $request)
    {
        $this->validate($request,[
            "xml" => "required"
        ]);

        /* For file that is too big , first store it in memory */
        $dataStr = file_get_contents($request->file('xml')->getRealPath());

        /* Use SimpleXML to import Data from XML File */
        $xmlObj = simplexml_load_string($dataStr);
        foreach($xmlObj->item as $importData)
        {
            $problemObj = new Problem();
            $testCaseObj = new Testcase();

            /* Fetch The basic info of the problem */
            $problemObj->title = $importData->title->__toString();
            $problemObj->visibility_locks = 0;
            $problemObj->description = $importData->description->__toString();
            $problemObj->time_limit = $importData->time_limit * 1;
            $problemObj->mem_limit = $importData->memory_limit * 1024;
            $problemObj->output_limit = 10000000;
            $problemObj->difficulty = 0;
            $problemObj->input = $importData->input->__toString();
            $problemObj->output = $importData->output->__toString();
            $problemObj->sample_input = $importData->sample_input->__toString();
            $problemObj->sample_output = $importData->sample_output->__toString();
            $problemObj->source = "NEUOJ-old";
            $problemObj->author_id = session('uid');
            $jsonData = json_encode($problemObj);
            $problemObj->description = $jsonData;

            /* Unset fields that don't exist the database */
            unset($problemObj->input);
            unset($problemObj->output);
            unset($problemObj->sample_input);
            unset($problemObj->sample_output);
            unset($problemObj->source);
            $problemObj->save();

            /* Fetch The testdata for the problem */
            $input_data = $importData->test_input;
            $output_data = $importData->test_output;
            $testCaseObj->pid = $problemObj->id;
            $testCaseObj->rank = 1;
            $testCaseObj->input_file_name = $testCaseObj->pid . "-" . time() . "-" ."in";
            $testCaseObj->output_file_name = $testCaseObj->pid . "-" . time() . "-" ."out";
            $testCaseObj->md5sum_input = md5($input_data);
            $testCaseObj->md5sum_output = md5($output_data);
            $testCaseObj->save();
            Storage::put('testdata/'. $testCaseObj->input_file_name, $input_data);
            Storage::put('testdata/'. $testCaseObj->output_file_name, $output_data);
        }
        return Redirect::to('/dashboard/problem/');
    }

    /*
     * @function changeVisibility
     * @input $request, $problem_id
     *
     * @return View or Redirect
     * @description changeVisibility when visibility change button is clicked. If no contest uses the problem and the problem is locked,
     *              unlock the problem, else show an error page
     *              If the contest is unlock, lock the contest
     */
    public function changeVisibility(Request $request, $problem_id)
    {
        $data = [];
        $problemObj = Problem::where('problem_id', $problem_id)->first();
        if($problemObj->visibility_locks != 0)
        {
            $contestProblemList = ContestProblem::where('problem_id', $problem_id)->get();
            if($problemObj->isUsedByContest())
            {
                $data['usedContestList'] = [];
                $i = 0;
                foreach($contestProblemList as $contestProblem)
                {
                    $contestObj = Contest::where('contest_id', $contestProblem->contest_id)->first();
                    if(!$contestObj->isEnded())
                    {
                        $data['usedContestList'][$i] = $contestProblem->contest_id;
                        $i++;
                    }
                }
                if($i != 0)
                {
                    return View::make("errors.unlock_failed")->with($data);
                }
            }
            Problem::where('problem_id', $problem_id)->update(['visibility_locks' => 0]);
            return Redirect::back();
        }
        Problem::where('problem_id', $problem_id)->update(['visibility_locks' => 1]);
        return Redirect::back();
    }

    /*
     * @function quickAccess
     * @input $request (use query)
     *
     * @return Redirect or View
     * @description Process the query and redirect to
     *              certain problem or list
     */
    public function quickAccess(Request $request)
    {
        $data = [];
        $query = $request->input('query');

        /* If it's a problem id, just jump to the problem */
        if(is_numeric($query))
        {
            return Redirect::to("/problem/$query");
        }
        else
        {
            if(RoleController::is('admin'))
            {
                $data['problems'] = Problem::where('title', 'like', "%$query%")->get();
            }
            else
            {
                /* Only problems with no visibility_lock can be access by normal user */
                $data['problems'] = Problem::where('visibility_locks', 0)->
                                    where('title', 'like', "%$query%")->get();
            }

            /* This code is duplicated now, need to encapsulate it */
            foreach ($data['problems'] as $problem)
            {
                $problem->submission_count = Submission::where('pid', $problem->problem_id)->count();
                $problem->ac_count = Submission::where('pid', $problem->problem_id)
                    ->where('result', 'Accepted')->count();
                $authorObj = User::where('uid', $problem->author_id)->first();
                $problem->author = $authorObj["username"];
                $problem->used_times = $problem->getNumberOfUsedContests();
            }
            $data['firstPage'] = 1;
            $data["lastPage"] = 1;
            $data['page_id'] = 1;
            $data['page_num'] = 1;
            return View::make('problem.list', $data);
        }
    }

    public function getTrainProblemByTrainProblemID(Request $request, $train_id, $chapter_id, $train_problem_id)
    {
        $data = [];
        $uid = $request->session()->get('uid');
        $trainingObj = Train::where('train_id', $train_id)->first();
        $trainProblemObj = TrainProblem::where([
            "train_id" => $train_id,
            "train_problem_id" => $train_problem_id,
        ])->first();

        if($trainingObj->getUserChapter($uid) < $chapter_id && !RoleController::is('admin'))
        {
            return Redirect::to("/training/$train_id");
        }

        $realProblemID = $trainProblemObj->problem_id;
        $problemObj = Problem::where('problem_id', $realProblemID)->first();
        $jsonObj = json_decode($problemObj->description);
        $data['problem'] = $problemObj;
        if($jsonObj != NULL)
        {
            foreach ($jsonObj as $key => $val)
            {
                $data['problem']->$key = $val;
            }
        }
        if($trainProblemObj->problem_title == "")
            $data['problem']->title = $trainProblemObj->problem->title;
        else
            $data['problem']->title = $trainProblemObj->problem_title;
        $data['training'] = $trainingObj;

        $data['problem']->acSubmissionCount = Submission::where([
            'pid' => $realProblemID,
            'result' => "Accepted"
        ])->get()->unique('uid')->count();

        $data['problem']->totalSubmissionCount = Submission::getValidSubmissionCount(0, $realProblemID);
        return View::make("problem.index", $data);
    }

    /*
     * @function getAllProblemTitleJSON
     * @input Request
     *
     * @return JSON
     * @description give all the problem_id => problem_title mapping
     *              in JSON format
     */
    public function getAllProblemTitleJSON(Request $request)
    {
        $problemObj = Problem::select('problem_id', 'title')->get();
        return $problemObj->toJson();
    }
}
