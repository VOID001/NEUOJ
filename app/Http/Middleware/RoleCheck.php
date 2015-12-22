<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redirect;
use App\Submission;
use App\User;

class RoleCheck
{
    public function handle($request, Closure $next, $role)
    {
        $uid = $request->session()->get('uid');
        if($role == "view")
        {
            $run_id = $request->route()->getParameter('run_id');
            $submissionObj = Submission::where('runid', $run_id)->first();
            /*
             * Future will change to RoleCheck
             * Support Role & Access Check function
             *
             */
            $userObj = User::where('uid', $uid)->first();
            $username = $userObj->username;
            if ($username == "VOID001" || $username == "admin")
            {
                return $next($request);
            }
            if (!$uid || $submissionObj->uid != $uid)
            {
                $vatr = $request->server();
                return Redirect::to('/status/');
            }
            return $next($request);
        }
        if($role == "admin")
        {
            $userObj = User::where('uid', $uid)->first();
            $username = $userObj->username;
            if($username == "VOID001" || $username == "admin")
            {
                return $next($request);
            }
            $session = $request->session();
            var_dump($session);
            return Redirect::to('/');
        }
    }
}