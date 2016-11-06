<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Redis;
use App\User;
use App\Contest;

class ChatroomController extends Controller
{
    public function showChatroomIndex(Request $request)
    {
        //return View::make('chatroom.index');
        return Redirect::back();
    }
    public function sendMessage(Request $request)
    {
        $input = $request->input();
        if(!$request->session()->has('uid'))
            return;
        $userObj = User::where('uid', $request->session()->get('uid'))->first();
        $redis = Redis::connection();
		$redis->publish('message', json_encode(
            [
                'username' => $request->session()->get('username'),
                'message' => $request->input('message'),
                'channel' => $request->input('contest')
            ]
        ));
		return;
    }

}
