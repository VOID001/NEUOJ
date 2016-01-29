<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redirect;

class Authenticate
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
        if($request->session()->get('username') == NULL)
        {
            $data['loginError'] = 'You need to sign in before proceed this action';
            $data['prevURL'] = $request->server('REQUEST_URI');
            //Store the previous visit URL into session
            $request->session()->put('sessiondat', $data);
            return Redirect::route('signin')->with($data);
        }
        return $next($request);
    }
}
