<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redirect;
use App\Submission;

class RoleCheck
{
    public function handle($request, Closure $next)
    {
        $uid = $request->session()->get('uid');
        $run_id = $request->route()->getParameter('run_id');
        $submissionObj = Submission::where('runid', $run_id)->first();
        /*
         * Future will change to RoleCheck
         * Support Role & Access Check function
         *
         */
        if(!$uid || $submissionObj->uid != $uid)
        {
            $vatr = $request->server();
            return Redirect::to('/status/');
        }
        return $next($request);
    }
}