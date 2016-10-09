<?php

namespace App\Http\Controllers;

use App\Http\Middleware\ProfileCheck;
use Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class JudgehostController extends Controller
{
    public function getIndex(Request $request)
    {
        return View::make('dashboard.judgehost');
    }

    public function getJudgeStatus(Request $request)
    {
        $data = [];

        if(!Cache::has('judgehost.ts'))
            return response()->json(NULL);
        $judgehostTimestamp = Cache::get('judgehost.ts');
        foreach($judgehostTimestamp as $key => $value)
        {
            $currentTs = time();
            if($currentTs - $value > 60)
            {
                $data[$key] = "Stopped";
            }
            else if($currentTs - $value > 30)
            {
                $data[$key] = "Error";
            }
            else if($currentTs - $value > 10)
            {
                $data[$key] = "Warning";
            }
            else
            {
                $data[$key] = "Normal";
            }
        }
        return response()->json($data);
    }

    public function startAll(Request $request) {
        return response()->json(NULL);
    }

    public function stopAll(Request $request) {
        return response()->json(NULL);
    }

    public function cleanAll(Request $request) {
        return response()->json(NULL);
    }
}

