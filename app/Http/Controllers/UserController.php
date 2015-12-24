<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\User;
use App\Userinfo;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Storage;

class UserController extends Controller
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function setProfile(Request $request)
    {
        $uid=$request->session()->get('uid');
        if(!$request->session()->has('profileError'))
            $data = [];
        else
            $data['profileError'] = $request->session()->get('profileError');
        if($request->method() == 'POST')
        {
            $input = $request->input();
            $image = $request->file('image');
            if(isset($image)){
                //image size < 1M
                if($image->getSize()>1048576)
                {
                    $data['profileError'] = "Image file is larger than 1M!";
                    $userinfoObject = Userinfo::where('uid',$uid)->first();
                    if(isset($userinfoObject)) {
                        $input['nickname'] = $userinfoObject->nickname;
                        $input['school'] = $userinfoObject->school;
                        $input['stu_id'] = $userinfoObject->stu_id;
                        if (!Storage::has('avatars/' . $uid . '.jpg')) {
                            $uid = 0;
                        }
                        $input['uid']=$uid;
                    }
                    //return "Image file is larger than 1M!";
                    return View::make('dashboard.profile', $data,$input);
                }
                else {
                    Storage::put(
                        'avatars/' . $uid. ".jpg",
                        file_get_contents($request->file('image')->getRealPath())
                    );
                }
            }
            $vdtor = Validator::make($input, [
                "nickname" => "max:255",
                "school" => "max:255",  //check school
            ]);
            $vdtor->sometimes('stu_id','required|max:255:unique',function($input){
                return $input->school == "NEU";
            });
            if($vdtor->fails()) {
                return Redirect::route('dashboard.profile')->withErrors($vdtor);
            }
            $userinfoObject=Userinfo::where('uid', $uid)->first();
            if(!isset($userinfoObject))
            {
                $userinfoObject = new Userinfo;
                $userinfoObject->uid = $uid;
                $userinfoObject->save();
            }
            Userinfo::where('uid', $uid)->update(['nickname' => $request->nickname]);
            Userinfo::where('uid', $uid)->update(['school' => $request->school]);
            Userinfo::where('uid', $uid)->update(['stu_id' => $request->stu_id]);
            return Redirect::route('dashboard.profile');
        }
        else {
            $userinfoObject = Userinfo::where('uid',$uid)->first();
            if(isset($userinfoObject)) {
                $input['nickname'] = $userinfoObject->nickname;
                $input['school'] = $userinfoObject->school;
                $input['stu_id'] = $userinfoObject->stu_id;
                if (!Storage::has('avatars/' . $uid . '.jpg')) {
                    $uid = 0;
                }
                $input['uid']=$uid;
            }
            return View::make('dashboard.profile', $input);
        }
    }

    public function setSettings(Request $request)
    {
        $uid=$request->session()->get('uid');
        if(!$request->session()->has('settingError'))
            $input = [];
        else
            $input['settingError'] = $request->session()->get('settingError');
        if($request->method() == 'POST')
        {
            $input = $request->input();
            $vdtor = Validator::make($input, [
                "old_pass" => "required|between:6,255",
                "pass" => "required|confirmed|between:6,255",
            ]);
            if($vdtor->fails()) {
                return Redirect::route('dashboard.settings')->withErrors($vdtor);
            }
            $userObject=User::where('uid', $uid)->first();
            $passHash = $userObject->password;
            if (Hash::check($input['old_pass'], $passHash)) {
                $userObject->where('uid', $uid)->update(['password' => Hash::make($input['pass'])]);
            }
            else
            {
                $input['settingError'] = "Invalid Password";
                return View::make('dashboard.settings', $input);
            }
            return Redirect::route('home');
        }
        else {
            return View::make('dashboard.settings', $input);
        }
    }

    public function showProfile(Request $request,$user_id)
    {
        $userinfoObject = Userinfo::where('uid',$user_id)->first();
        if(isset($userinfoObject)) {
            $input['nickname']=$userinfoObject->nickname;
            $input['school']=$userinfoObject->school;
            $input['stu_id']=$userinfoObject->stu_id;
            return View::make('home.profile', $input);
        }
        else{
            return Redirect::route('home');
        }
    }

    public function showAvatar(Request $request,$user_id)
    {
        $user = User::where('uid',$user_id)->first();
        if(!isset($user)) {
            $user_id=0;
        }
        $file = Storage::get('avatars/' . $user_id . ".jpg");
        $type = Storage::mimeType('avatars/' . $user_id . ".jpg");
        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);
        return $response;
    }
}
