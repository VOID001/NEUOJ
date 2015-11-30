<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;

use App\User;
use App\Problem;

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
        $data = $problemObj->first();
        //var_dump($problemObj);
        return View::make("problem.index", $problemObj);
    }

    public function getProblemListByPageID(Request $request, $page_id)
    {
        $problemPerPage = 10;
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
        $data = [];
        $data['problems'] = NULL;
        $data['problemPerPage'] = $problemPerPage;
        $data['page_id'] = $page_id;
        $problemObj = Problem::where('visibility_locks', 0)->orderby('problem_id', 'asc')->get();
        for($count = 0, $i = ($page_id - 1) * $problemPerPage; $count < $problemPerPage && $i < $problemObj->count(); $i++, $count++)
        {
            $data['problems'][$count] = $problemObj[$i];
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
}
