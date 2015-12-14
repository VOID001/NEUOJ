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

class UserController extends Controller
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function setProfile(Request $request)
    {
        $uid=$request->session()->get('uid');
        $input=[];
        if($request->method() == 'POST')
        {
            $input = $request->input();
            $vdtor = Validator::make($input, [
                "nickname" => "max:255",
                "school" => "max:255",  //check school
                "stu_id" => "max:255"  //check stu_id unique
            ]);
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
            return Redirect::route('home');
        }
        else {
            $userinfoObject = Userinfo::where('uid',$uid)->first();
            if(isset($userinfoObject)) {
                $input['nickname']=$userinfoObject->nickname;
                $input['school']=$userinfoObject->school;
                $input['stu_id']=$userinfoObject->stu_id;
            }
            return View::make('dashboard.profile', $input);
        }
    }
}
