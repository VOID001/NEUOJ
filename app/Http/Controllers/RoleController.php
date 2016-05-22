<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Problem;

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
        if($uid && $uid <= 2)
        {
            return true;
        }
        return false;
    }

    /**
     * @function is
     * @input $role
     *
     * @return bool
     * @description check whether current user is $role
     */
    public static function is($role)
    {
        switch($role)
        {
            case "admin":
                return RoleController::checkAdmin();
        }
    }
}
