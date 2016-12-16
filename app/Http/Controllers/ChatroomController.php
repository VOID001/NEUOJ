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
		$redis->publish($channel, json_encode(
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

    /**
     * @function getlastrecord
     * @param $channel
     * @param $recordCount
     * @return json
     * @description get the last record in json type
     *
     */
    public function getlastrecord($channel, $recordCount)
    {
        $count = 0;
        $data = [];
        $record = [];
        $dirPath = 'chatroomLog';
        $directories = Storage::directories($dirPath);
        for ($i = 0; $i < sizeof($directories); $i++) {
            $filePath = $directories[$i];
            $files = Storage::Files($filePath);
            for ($j = 0; $j < sizeof($files); $j++) {
                $recordfile[$i][$j] = Storage::get($files[$j]);
                $recordExplode = explode("\n", $recordfile[$i][$j]);
                for ($k = 0; $k < sizeof($recordExplode);) {
                    $recordExplodeAgain['first'] = explode(" ", $recordExplode[$k++]);
                    $recordExplodeAgain['second'] = explode(":", $recordExplode[$k++]);
                    $data[$count]['channel'] = $recordExplodeAgain['first'][3];
                    $data[$count]['time'] = $recordExplodeAgain['first'][5];
                    $data[$count]['username'] = $recordExplodeAgain['first'][1];
                    $data[$count]['message'] = $recordExplodeAgain['second'][1];
                    $count++;
                }
            }
        }
        for ($i = 0, $j = $count - 1; $i < $recordCount && $j >= 0; $i++, $j--) {
            while ($j >= 0) {
                if ($data[$j]['channel'] == $channel) {
                    $record[$i] = $data[$j];
                    break;
                } else
                    $j--;
            }
        }
        if ($i < $recordCount) {
            if (isset($record[0]))
                $record['status'] = "Records not enough";
            else
                $record['status'] = "No records";
        }else
            $record['status'] = "Success";
        return json_encode($record);
    }
}
