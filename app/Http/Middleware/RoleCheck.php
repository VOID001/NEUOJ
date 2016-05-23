<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redirect;
use App\Submission;
use App\User;
use App\Http\Controllers\RoleController;

class RoleCheck
{
    public function handle($request, Closure $next, $role)
    {
        $uid = $request->session()->get('uid');
        if($role == "balloon")                  //tmp balloon role check
        {
            if($uid == 763 || $uid <=2 )
                return $next($request);
            return Redirect::back();
        }
        if($role == "view-code")
        {
            $run_id = $request->route()->getParameter('run_id');
            //$submissionObj = Submission::where('runid', $run_id)->first();
            $param['runid'] = $run_id;
            ///*
            // * Future will change to RoleCheck
            // * Support Role & Access Check function
            // *
            // */
            //$roleCheck = new RoleController;
            ////$userObj = User::where('uid', $uid)->first();
            ////$username = $userObj->username;
            ////if ($username == "VOID001" || $username == "admin")
            //if($roleCheck->is('admin') || $roleCheck->is('teacher'))
            //{
            //    return $next($request);
            //}
            //if (!$uid || $submissionObj->uid != $uid)
            //{
            //    $vatr = $request->server();
            //    return Redirect::to('/status/');
            //}
            $roleCheck = new RoleController;
            if($roleCheck->is("able-view-code", $param))
                return $next($request);
            return Redirect::back();
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
            //var_dump($session);
            return Redirect::to('/');
        }

        if($role == "judge")
        {
            $judge_username = $request->server('PHP_AUTH_USER');
            $judge_password = $request->server('PHP_AUTH_PW');
            /* Query Judge Account From Database Stub Now */
            if($judge_username != NULL)
            {
                // Do nothing now
            }
            if($judge_password == env('JUDGE_PW', 'neuoj') && $judge_username == env('JUDGE_USER', 'neuoj'))
                return $next($request);

            if(env('APP_DEBUG') == 'true')
                return "\n[ERROR] Judge name or password wrong";
            else
                abort(404);
        }
    }
}