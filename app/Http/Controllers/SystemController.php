<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;

class SystemController extends Controller
{
    public function getSystemSummary(Request $request)
    {
        return View::make('dashboard.system');
    }
}
