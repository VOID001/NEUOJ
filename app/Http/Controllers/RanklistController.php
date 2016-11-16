<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Userinfo;
use App\Submission;

class RanklistController extends Controller
{

    public function getRanklist(Request $request)
    {
        return Redirect::to('/ranklist/p/1');
    }

    /**
     * @function getRanklistByPageID
     * @input $request, $page_id
     *
     * @return Redirect or View
     * @description show ranklist page
     */
    public function getRanklistByPageID(Request $request, $page_id)
    {
        $data = [];
        $data['ranklist'] = [];
        $user_per_page = 50;
        $ranklist = Userinfo::select("uid", "stu_id", "nickname", "school", "ac_count", "submit_count")->where("submit_count", "<>", "0")->get();
        if($user_per_page * ($page_id - 1) > count($ranklist))
            return Redirect::to("/");
        $j = 0;
        foreach($ranklist as &$rank)
        {
            $rank->ac_ratio = round($rank->ac_count / $rank->submit_count * 100, 2)."%";
        }
        $ranklist = $ranklist->all();
        usort($ranklist, [$this, 'cmp']);
        for($i = $user_per_page * ($page_id - 1); $i < ($user_per_page * $page_id > count($ranklist) ? count($ranklist) : $user_per_page * $page_id); $i++)
        {
            $data['ranklist'][$j] = $ranklist[$i];
            $j++;
        }
        $data['counter'] = 1;
        $data['page_num'] = ceil(count($ranklist) / $user_per_page);
        $data['page_id'] = $page_id;
        $data['page_user'] = $user_per_page;
        return View::make('ranklist.index')->with($data);
    }

    public function cmp($userA, $userB)
    {
        if($userA['ac_count'] == $userB['ac_count'])
        {
            return $userA['ac_ratio'] < $userB['ac_ratio'];
        }
        return $userA['ac_count'] < $userB['ac_count'];
    }

    /**
     * @function initRanklist
     * @input $request
     *
     * @return Redirect
     * @description init user ac_count and submit_count in userinfo table
     */
    public function initRanklist(Request $request)
    {
        $userInfoObj = Userinfo::all();
        foreach($userInfoObj as &$userInfo)
        {
            $userInfo->ac_count = Submission::select('uid', 'pid')->where(['uid' => $userInfo->uid, 'result' => 'Accepted'])->get()->unique('pid')->count();
            $userInfo->submit_count = Submission::select('uid')->where('uid', $userInfo->uid)->count();
            $userInfo->save();
            echo "<pre>";
            echo $userInfo."<br>";
        }
        return Redirect::to('/ranklist/p/1');
    }
}
