<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;

use App\User;
use App\Problem;
use App\Submission;
use App\Testcase;
use Storage;
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
        $data['problems'] = NULL;
        $data['problemPerPage'] = $problemPerPage;
        $data['page_id'] = $page_id;
        $problemObj = Problem::where('visibility_locks', 0)->orderby('problem_id', 'asc')->get();
        for($count = 0, $i = ($page_id - 1) * $problemPerPage; $count < $problemPerPage && $i < $problemObj->count(); $i++, $count++)
        {
            $data['problems'][$count] = $problemObj[$i];
            $data['problems'][$count]->submission_count = Submission::where('pid', $problemObj[$i]->problem_id)->count();
            $data['problems'][$count]->ac_count = Submission::where('pid', $problemObj[$i]->problem_id)
                ->where('result', 'Accepted')->count();
            $authorObj = User::where('uid', $problemObj[$i]->author_id)->first();
            $data['problems'][$count]->author = $authorObj["username"];
        }
        if($i >= $problemObj->count())
        {
            $data['lastPage'] = true;
        }
        if($page_id == 1)
        {
            $data['firstPage'] = true;
        }
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
        $problemObj = Problem::all();
        $data[] = NULL;
        if(session('status'))
        {
            $data['status'] = session('status');
        }
        for($count = 0, $i = ($page_id - 1) * $problemPerPage; $count < $problemPerPage && $i < $problemObj->count(); $count++, $i++)
        {
            $data['problems'][$count] = $problemObj[$i];
            $userObj = User::where('uid', $problemObj[$i]->author_id)->first();
            $data['problems'][$count]->submission_count = Submission::where('pid', $problemObj[$i]->problem_id)->count();
            $data['problems'][$count]->ac_count = Submission::where('pid', $problemObj[$i]->problem_id)
                ->where('result', 'Accepted')->count();
            $data['problems'][$count]->author = $userObj->username;
        }
        if($page_id == 1)
        {
            $data['firstPage'] = true;
        }
        if($i >= $problemObj->count())
        {
            $data['lastPage'] = true;
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
            $updateProblemData['description'] = json_encode($jsonObj);
            //var_dump($input);
            Problem::where('problem_id', $problem_id)->update($updateProblemData);
            /*
             * Check if testcase files are changed
             */
            $uploadInput = $request->file('input_file');
            $uploadOutput = $request->file('output_file');
            var_dump($uploadInput[0]);
            if(count($uploadInput) == count($uploadOutput) && $uploadInput[0] && $uploadOutput[0])
            {
                Testcase::where('pid', $problem_id)->delete();
                for($i = 0; $i < count($uploadInput); $i++)
                {
                    $updateTestcaseData['rank'] = $i + 1;
                    $updateTestcaseData['input_file_name'] = $problem_id . "-" . time() . "-" . $uploadInput[$i]->getClientOriginalName();
                    $updateTestcaseData['output_file_name'] = $problem_id . "-". time() . "-" . $uploadOutput[$i]->getClientOriginalName();
                    $updateTestcaseData['pid'] = $problem_id;
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
        return "This is for MiaoP XD";
    }

    public function getContestProblemByContestProblemID(Request $request, $contest_id, $problem_id)
    {
        return "ProblemController@getContestProblemByContestProblemID";
    }

}
