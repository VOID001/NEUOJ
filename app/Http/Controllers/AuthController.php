<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\User;
use App\Userinfo;
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
        if(!($request->session()->has('loginError')))
            $data = [];
        else
            $data['loginError'] = $request->session()->get('loginError');
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
                    /* Save ip into session before update it */
                    if($row->lastlogin_ip != "") $request->session()->put('lastlogin_ip', $row->lastlogin_ip);
                    $userObject->where('uid', $row->uid)->update([
                        'lastlogin_ip' => $request->ip(),
                        'lastlogin_time' => date('Y-m-d h:i:s')
                    ]);
                    $request->session()->put([
                        'username' => $row->username,
                        'uid' => $row->uid,
                    ]);
                    if($request->session()->get('sessiondat') != NULL)
                    {
                        $sessionDat = $request->session()->get('sessiondat');
                        $prevURL = $sessionDat['prevURL'];
                        return Redirect::to($prevURL);
                    }
                    return Redirect::route('home');
                }
                $data['loginError'] = "Invalid Password";
            }
            else
            {
                $data['loginError'] = "No such user!";
                unset($data['username']);
            }
        }
        return View::make('auth.signin', $data);
    }

    public function registAction(Request $request)
    {
        $input = [];
        $data = [];
        if($request->method() == 'POST')
        {
            $input = $request->input();
            $vdtor = Validator::make($input, [
                "username" => "required|max:255|unique:users",
                "pass" => "required|confirmed|between:6,255",
                "email" => "required|email|unique:users"
            ]);
            if($vdtor->fails())
            {
                return Redirect::route('signup')->withErrors($vdtor)->withInput($input);
            }
            $userObject = new User;
            $userObject->username = $request->username;
            $userObject->password = Hash::make($request->pass);
            $userObject->email = $request->email;
            $userObject->registration_time = date('Y-m-d h:i:s');
            $userObject->save();

            $userObject->where('username', $request->username)->update(['lastlogin_ip' => $request->ip()]);
            $userObject->where('username', $request->username)->update(['regsitration_ip' => $request->ip()]);

            //after $userObject->save(), $userObject->uid is not available
            $userObject=User::where('username',$request->username)->first();
            //Keep this session if you need auto-login after sign up
            $request->session()->put([
                'username' => $userObject->username,
                'uid' => $userObject->uid,
            ]);
            $userinfoObject = new Userinfo;
            $userinfoObject->uid = $userObject->uid;
            $userinfoObject->save();
            return Redirect::route('dashboard.profile');
        }
        return View::make('auth.signup', $data);
    }

    public function logoutAction(Request $request)
    {
        $request->session()->flush();
        return Redirect::route('home');
    }
}


