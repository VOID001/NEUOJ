<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Storage;
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
        if($input['contest'] != 0)
        {
            if($request->session()->has('channel_list'))
            {
                $user_channel_list = $request->session()->get('channel_list');
                if(!collect($user_channel_list)->contains($input['contest']))
                    return;
            }
            else
                return;
        }
        $redis = Redis::connection();
        $username = $request->session()->get('username');
        $message = $request->input('message');
        $channel = $request->input('contest');
        $time = date('Y-m-d-H:i:s');
        $logtime = date('Y-m-d');
		$redis->publish('message', json_encode(
            [
                'username' => $username,
                'message' => $message,
                'channel' => $channel,
                'time' => $time,
            ]
        ));
        $content = "username: $username channel: $channel time: $time\nmessage:$message";
        $path = "chatroomLog/$logtime/$logtime-channel-$channel.log";
        if(Storage::has($path))
            Storage::append($path, $content);
        else
            Storage::put($path, $content);
		return;
    }

}
