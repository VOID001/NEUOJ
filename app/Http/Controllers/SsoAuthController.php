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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function casloginAction(Request $request)
    {
        phpCAS::client(CAS_VERSION_2_0, "sso.neu.cn", 443, "/cas");
        phpCAS::setFixedServiceURL($request->url());
        phpCAS::setNoCasServerValidation();
        phpCAS::forceAuthentication();
        $user_id = phpCAS::getUser();
        $user = User::where('bindSSO', $user_id)
            ->orWhere('username', $user_id)
            ->first();
        if (!$user) {
            $newUser = new User;
            $newUser->username = $user_id;
            $newUser->password = Hash::make($user_id);
            $newUser->email = $user_id . "@stu.neu.edu.cn";
            $newUser->bindSSO = $user_id;
            $newUser->registration_time = date('Y-m-d h:i:s');
            $newUser->lastlogin_time = date('Y-m-d h:i:s');
            $newUser->regsitration_ip = $request->ip();
            $newUser->lastlogin_ip = $request->ip();
            $newUser->save();

            $newUserInfo = new Userinfo;
            $newUserInfo->uid = $newUser->uid;
            $newUserInfo->school = "NEU";
            $newUserInfo->stu_id = $newUser->username;
            $newUserInfo->save();

            $request->session()->put([
                'username' => $newUser->username,
                'uid' => $newUser->uid,
                'gid' => $newUser->gid,
            ]);
            return Redirect::route('dashboard.profile');
        } else {
            // dump($user_id);
            $request->session()->put([
                'username' => $user->username,
                'uid' => $user->uid,
                'gid' => $user->gid,
            ]);
            $user->lastlogin_ip = $request->ip();
            $user->lastlogin_time = date('Y-m-d h:i:s');
            $user->save();
            if ($request->session()->get('sessiondat') != NULL) {
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
