<?php

namespace App\Http\Controllers;

use App\Contest;
use App\ContestProblem;
use App\Problem;
use App\User;
use App\Http\Controllers\Controller;
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


}


