<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class AuthController extends Controller
{
    /*
     * @params $request
     * @comment handle GET & POST requests
     * and validate the post data then proceed login action
     *
     * @return View or Redirect
     */
    public function loginAction(Request $request)
    {
        $input = [];
        $data = [];
        if($request->method() == 'POST')
        {
            $input = $request->input();
            $vdtor = Validator::make($input, [
                "username" => "required|max:255",
                "pass" => "required"
            ]);
            if($vdtor->fails())
            {
                return Redirect::route('signin')->withErrors($vdtor)->withInput($input);
            }
            $userObject = new User;
            $row = $userObject->where('username',$input['username'])->first();
            $data['username'] = $input['username'];
            if(isset($row))
            {
                $passHash = $row->password;
                if (Hash::check($input['pass'], $passHash))
                {
                    $request->session()->put('username', $row->username);
                    return Redirect::route('home');
                }
                $data['loginError'] = "Invalid Password";
            }
            else
            {
                $data['loginError'] = "No such user!";
            }
        }
        return View::make('auth.signin', $data);
    }
}


