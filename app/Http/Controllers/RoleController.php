<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Problem;
use App\User;
use App\Submission;

class RoleController extends Controller
{
    /**
     * @function checkAdmin
     * @input none
     *
     * @return bool
     * @description check if the current user is admin
     */
    public static function checkAdmin()
    {
        $uid = session('uid');
        /*** Use database gid field to check Admin */
//        $userObj = User::find($uid);
        $gid = session('gid');
        if(!$uid)
            return false;
        if($gid == env('GROUP_ADMIN', 1))
            return true;
        return false;
    }

    /**
     * @function checkTeacher
     * @input none
     *
     * @return bool
     * @description check if the user is teacher
     *              teacher can only view code at the moment
     */
    public static function checkTeacher()
    {
        $uid = session('uid');
//        $userObj = User::find($uid);
        $gid = session('gid');
        if(!$uid)
            return false;
        if($gid == env('GROUP_TEACHER', 2))
            return true;
        return false;
    }

    /**
     * @function checkAbleViewCode
     * @input runid
     *
     * @return bool
     * @description check the user's ability of view code
     */
    public static function checkAbleViewCode($runid)
    {
        $uid = session('uid');
        if(RoleController::is("admin") || RoleController::is("teacher"))
            return true;
        $submissionObj = Submission::find($runid);
        if(!$submissionObj)
            return false;

        // Check whether the user has AC the problem
        $acObj = Submission::where([
            'uid' => $uid,
            'pid' => $submissionObj->pid,
            'result' => "Accepted",
        ])->first();

        if($submissionObj->uid == $uid)
            return true;
        else if($acObj != NULL && $submissionObj->result == "Accepted")     // And the problem should be ACed by others
            return true;
        return false;
    }
    /**
     * @function is
     * @input $role(string) $param(mixed)
     *
     * @return bool
     * @description check whether current user is $role
     */
    public static function is($role, $param = NULL)
    {
        switch($role)
        {
            /** First is basic roleCheck */
        case "admin":
            return RoleController::checkAdmin();
        case "teacher":
            return RoleController::checkTeacher();
            /** Then is ability check */
        case "able-view-code":
            return RoleController::checkAbleViewCode($param['runid']);
        }
        return false;
    }
}
