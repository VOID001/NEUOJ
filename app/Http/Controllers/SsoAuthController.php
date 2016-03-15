<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use App\User;
use App\Userinfo;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use phpCAS;
use App\Http\Controllers\View;

class SsoAuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function casloginAction(Request $request)
    {
        phpCAS::client(CAS_VERSION_2_0, "sso.neu.cn", 443, "/cas");
        phpCAS::setNoCasServerValidation();
        phpCAS::forceAuthentication();
        $user_id = phpCAS::getUser();
        $username = User::where('username', $user_id)->first();
        if(!$username)
        {
            $userObject = new User;
            $userObject->username = $user_id;
            $userObject->password = Hash::make($username);
            $userObject->email = $user_id."@stu.neu.edu.cn";
            $userObject->registration_time = date('Y-m-d h:i:s');
            $userObject->lastlogin_time = date('Y-m-d h:i:s');
            $userObject->regsitration_ip = $request->ip();
            $userObject->lastlogin_ip = $request->ip();
            $userObject->save();

            $userinfoObject = new Userinfo;
            $userinfoObject->uid = $userObject->uid;
            $userinfoObject->school = "NEU";
            $userinfoObject->stu_id = $userObject->username;

            $userinfoObject->save();

            $request->session()->put([
                'username' => $userObject->username,
                'uid' => $userObject->uid,
            ]);
            return Redirect::route('dashboard.profile');
        }
        else{
            // dump($user_id);
            $request->session()->put([
                'username' => $username->username,
                'uid' => $username->uid,
            ]);
            $username->lastlogin_ip = $request->ip();
            $username->lastlogin_time = date('Y-m-d h:i:s');
            $username->save();
            if($request->session()->get('sessiondat') != NULL)
            {
                $sessionDat = $request->session()->get('sessiondat');
                $prevURL = $sessionDat['prevURL'];
                return Redirect::to($prevURL);
            }
            return Redirect::route('home');
        }

    }

    public function caslogout(Request $request)
    {
        $request->session()->flush();
    }
}
