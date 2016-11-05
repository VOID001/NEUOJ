<?php

namespace App\Http\Controllers;

use App\OJLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\User;
use App\Userinfo;
use App\Submission;
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
	    $input = [];
        if(!$request->session()->has('profileError'))
            $data = [];
        else
            $data['profileError'] = $request->session()->get('profileError');
        if($request->method() == 'POST')
        {
            $input = $request->input();
            $image = $request->file('image');
            if(isset($image)){
                /* image size < 1M */
                if($image->getSize()>1048576)
                {
                    $data['profileError'] = "Image file is larger than 1M!";
                    $userinfoObject = Userinfo::where('uid',$uid)->first();
                    if(isset($userinfoObject))
                    {
                        $input['nickname'] = $userinfoObject->nickname;
                        $input['school'] = $userinfoObject->school;
                        $input['stu_id'] = $userinfoObject->stu_id;
                        if (!Storage::has('avatars/' . $uid . '.jpg'))
                        {
                            $uid = 0;
                        }
                        $input['uid']=$uid;
                    }
                    /* return "Image file is larger than 1M!" */
                    return View::make('dashboard.profile', $data, $input);
                }
                /* image mime type error */
                elseif(substr($image->getMimeType(), 0, 6) != "image/")
                {
                    $data['profileError'] = "Image file type error!";
                    $userinfoObject = Userinfo::where('uid',$uid)->first();
                    if(isset($userinfoObject))
                    {
                        $input['nickname'] = $userinfoObject->nickname;
                        $input['school'] = $userinfoObject->school;
                        $input['stu_id'] = $userinfoObject->stu_id;
                        if (!Storage::has('avatars/' . $uid . '.jpg'))
                        {
                            $uid = 0;
                        }
                        $input['uid']=$uid;
                    }
                    /* return "Image file type error!" */
                    return View::make('dashboard.profile', $data, $input);
                }
                else
                {
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
            Userinfo::where('uid', $uid)->update(['realname' => $request->realname]);
            $oldProfile = "nickname:" . $userinfoObject->nickname . " realname:" . $userinfoObject->realname . " school:" . $userinfoObject->school . " sut_id:" . $userinfoObject->stu_id;
            $newProfile = "nickname:" . $request->nickname . " realname:" . $request->realname . " school:" . $request->school . " sut_id:" . $request->stu_id;
            OJLog::changeProfile($uid, $oldProfile, $newProfile);
            return Redirect::route('dashboard.profile');
        }
        else {
            $userinfoObject = Userinfo::where('uid',$uid)->first();
            if(isset($userinfoObject)) {
                $input['nickname'] = $userinfoObject->nickname;
                $input['school'] = $userinfoObject->school;
                $input['stu_id'] = $userinfoObject->stu_id;
                $input['realname'] = $userinfoObject->realname;
                $input['acCount'] = json_encode(Submission::getAcCountByUserID($uid));
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
                "old_pass" => "required",
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
            OJLog::changePassword($uid);
            return Redirect::route('home');
        }
        else {
            return View::make('dashboard.settings', $input);
        }
    }

    public function showProfile(Request $request,$user_id)
    {
        $userinfoObject = Userinfo::where('uid',$user_id)->first();
        $userObj = User::where('uid', $user_id)->first();
        if(isset($userinfoObject)) {
            $input['nickname']=$userinfoObject->nickname;
	    $input['uid'] = $user_id;
            $input['school']=$userinfoObject->school;
            $input['stu_id']=$userinfoObject->stu_id;
	    $input['username'] = $userObj->username;
            return View::make('home.profile', $input);
        }
        else{
            return Redirect::route('home');
        }
    }

    public function showAvatar(Request $request,$user_id)
    {
        $user = User::where('uid',$user_id)->first();
        //if(!isset($user)) {
        //    $user_id = 0;
        //}
        if(Storage::has('avatars/' . $user_id . ".jpg") == NULL)
            $user_id = 0;
        $file = Storage::get('avatars/' . $user_id . ".jpg");
        $type = Storage::mimeType('avatars/' . $user_id . ".jpg");
        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);
        $response->header("Cache-Control", "max-age=60000");
        return $response;
    }

    /**
     * @function getDashboardIndex
     * @input $request
     *
     * @return Redirect
     * @description Helper function used for replace the Closure
     *              in the route to optimize performance
     */
    public function getDashboardIndex(Request $request)
    {
        return Redirect::route('dashboard.profile');
    }

    public function getUserDashboard(Request $request)
    {
        $data = [];
        $gid = 0;
        $search_username = "";
        if($request->input('page_id') != NULL)
            $page_id = $request->input('page_id');
        else
            $page_id = 1;
        if($request->input('role') != NULL)
        {
            if($request->input('role') == "teacher")
                $gid = 2;
            else if($request->input('role') == "admin")
                $gid = 1;
            else
                $gid = 0;
        }
        if($request->input('username') != NULL)
        {
            if($request->input('username') != "")
                $search_username = $request->input('username');
            else
                $search_username = "";
        }
        $user_per_page = 20;
        $data = User::getUserInPage($user_per_page, $page_id, $gid, $search_username);
        $data['role'] = $request->input('role');
        $data['search_username'] = $search_username;
        return View::make('dashboard.users')->with($data);
    }

    public function toggleUserPermission(Request $request)
    {
        $gid = $request->input('gid');
        $uid = $request->input('uid');
        if($gid == NULL || $uid == NULL)
            return Redirect::to('/');
        $userObj = User::where('uid', $uid)->first();
        if($userObj == NULL)
            return Redirect::back();
        if($userObj->gid == env('GROUP_ADMIN', 1) && $gid != env('GROUP_ADMIN', 1))
            return Redirect::back();
        $userObj->gid = $gid;
        $userObj->save();
        $adminId = $request->session()->get('uid');
        switch ($gid) {
            case 0:
                OJLog::setUser($adminId, $uid);
                break;
            case 1:
                OJLog::setAdmin($adminId, $uid);
                break;
            case 2:
                OJLog::setTeacher($adminId, $uid);
                break;
        }
        return Redirect::back();
    }
}
