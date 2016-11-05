<?php

namespace App\Http\Controllers;

use App\OJLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\User;
use App\Userinfo;
use Mail;
use Carbon\Carbon;
use App\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class AuthController extends Controller
{
    /**
     * @param  $request
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
                "pass" => "required",
                "captcha" => "required|captcha",
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
                    /*** Save ip into session before update it */
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
                    OJLog::loginInfo($row->uid, $request->ip());
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
                "username" => "required|max:255|unique:users|notpurenumber",
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
        $uid = $request->session()->get('uid');
        OJLog::logoutInfo($uid, $request->ip());
        $request->session()->flush();
        return Redirect::route('home');
    }

    /**
     * @function resetPasswordAction
     * @input $request
     *
     * @return Redirect or View
     * @description Check the token and give the form for reset password
     *              if all input is valid then reset the password
     *              and delete the token
     *
     */
    public function resetPasswordAction(Request $request)
    {
        $data = [];
        $token = $request->get('token');
        $now = Carbon::now();

        if($request->method() == "GET")
        {
            if ($token == NULL)
                return Redirect::to('/');

            $pwdResetObj = PasswordReset::where('token', $token)->first();

            /** A series of check to ensure the token is valid and not expired */
            if ($pwdResetObj == NULL)
            {
                $data['no_token'] = true;
                return View::make('auth.reset', $data);
            }
            if($now->diffInMinutes($pwdResetObj->created_at) > 10)
            {
                $data['no_token'] = true;
                PasswordReset::where('token', $token)->delete();
                return View::make('auth.reset', $data);
            }

            $data['token'] = $token;
            $data['email'] = $pwdResetObj->email;
            $data['username'] = User::where('email', $pwdResetObj->email)->first()->username;
            return View::make('auth.reset', $data);
        }
        else
        {
            $this->validate($request, [
                "token" => "required",
                "email" => "required | email",
                "new_password" => "required | min:6 | max:255",
                "confirm_password" => "required | same:new_password"
            ]);

            $input = $request->all();
            $data = $input;

            /*** We must check if the token is correspond with the email */
            $pwdResetObj = PasswordReset::where('token', $input['token'])->first();
            if($pwdResetObj == NULL)
            {
                $data['no_token'] = true;
                return View::make('auth.reset', $data);
            }
            if($pwdResetObj->email != $input['email'])
            {
                return View::make('auth.reset', $data)->withErrors("Email and token mismatch!");
            }
            $new_password = Hash::make($input['new_password']);

            User::where('username', $input['username'])->update([
                'password' => $new_password
            ]);
            $data['reset_ok'] = true;

            /*** At last we should delete the token */
            PasswordReset::where('token', $input['token'])->delete();
            return View::make('auth.reset', $data);
        }
    }

    /**
     * @function requestResetAction
     * @input $request
     *
     * @return Redirect or View
     * @description provide the form for request a password reset
     *              check whether the input is valid and then
     *              send the email to user
     */
    public function requestResetAction(Request $request)
    {
        $data = [];
        if($request->method() == "POST")
        {
            $this->validate($request,[
                "email" => "required | email",
                "captcha" => "required | captcha"
            ]);
            $email = $request->get('email');
            $pwdResetObj = new PasswordReset;
            $userObj = User::where('email', $email)->get();
            if($userObj->isEmpty())
            {
                $errorMsg = "Your Email address is not registered!";
                return View::make('auth.request')->withErrors($errorMsg);
            }
            if(PasswordReset::where('email', $email)->first() != NULL)
            {
                PasswordReset::where('email', $email)->delete();
                $errorMsg = "Your previous reset request has been canceled and new request sent to your mail";
            }
            $pwdResetObj->email = $email;
            $pwdResetObj->token = sha1($email . "" . time());

            Mail::send('email.request', ['passwordReset' => $pwdResetObj], function($message) use ($pwdResetObj){
                $message->from('neu_oj@163.com', 'NEUOJ');
                $message->to($pwdResetObj->email);
                $message->subject('[NEUOJ] Reset Password Confirmation');
            });
            $data['info'] = "You will recieve an email for password reset, check for your mailbox";
            $pwdResetObj->save();
        }

        if(isset($errorMsg))
            return View::make('auth.request', $data)->withErrors($errorMsg);
        else
            return View::make('auth.request', $data);
    }
}


