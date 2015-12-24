<?php

namespace App\Http\Middleware;
use Closure;
use App\User;
use App\Userinfo;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Request;

class ProfileCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $uid = $request->session()->get('uid');
        $userinfoObject = Userinfo::where('uid',$uid)->first();
        if(($request->route()->getName() != "dashboard.profile") && ($uid != NULL) && (( $userinfoObject == NULL) || ($userinfoObject->school == NULL) || ($userinfoObject->school == "NEU" && $userinfoObject->stu_id == NULL)))
        {
            $data['profileError'] = 'You need to complete your profile first.';
            return Redirect::route('dashboard.profile')->with($data);
        }
        return $next($request);
    }
}