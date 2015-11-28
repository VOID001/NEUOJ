<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class AuthController extends Controller
{
    public function loginAction(Request $request)
    {
        $input = [];
        if($request->method() == 'POST')
        {
            $input = $request->input();
            var_dump($input);
            $userObject = new User;
            $row = $userObject->where('username',$input['userName'])->firstOrFail();
            $passHash = $row->password;
            if(Hash::check($input['pass'], $passHash))
            {
                $request->session()->put('userName',$row->username);
                return Redirect::route('home');
            }
        }

        return View::make('auth.signin');
    }
}


