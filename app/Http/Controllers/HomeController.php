<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Response;

class HomeController extends Controller
{
    public function showHome(Request $request)
    {
        $userData=[];
        if($request->session()->get('username') != null)
        {
            $userData = $request->session()->all();
        }
        else
        {
            $userData['noLogin'] = true;
        }

        return View::make('home.index', $userData);
    }

}
