<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Validator;
use Storage;
use App\Http\Controllers\AuthController;
use App\Problem;
use App\User;
use App\Submission;

class SubmissionController extends Controller
{
    //public function getSubmissionListByPageID();
    //public function getSubmissionByID();
    //public function getSubmissionBySearch();

    public function submitAction(Request $request, $problem_id)
    {
        if($request->method() == "POST")
        {
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
            $fileName = $uid."-".$problem_id."-".time().".".$langsufix[$request->input('lang')];
            echo $fileName;
            Storage::put($fileName, $request->input('code'));
            $submission->pid = $problem_id;
            $submission->uid = $uid;
            $submission->cid = 0;
            $submission->result = "Pending";
            $submission->submit_time = date('Y-m-d-H:i:s');
            $submission->submit_file = $fileName;
            $submission->md5sum = md5($request->input('code'));
            $submission->save();
            var_dump($submission);
            return Redirect::to($request->server('HTTP_REFERER'));
        }
    }

}
