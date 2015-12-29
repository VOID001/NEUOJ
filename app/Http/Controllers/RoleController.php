<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Problem;

class RoleController extends Controller
{
    public function checkAdmin()
    {
        $uid = session('uid');
        if($uid && $uid <= 2)
        {
            return true;
        }
        return false;
    }
}
