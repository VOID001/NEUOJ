<?php

namespace App\Http\Controllers;

use App\OJLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Http\Requests;
use App\Thread;
use Validator;
use Illuminate\Support\MessageBag;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;

class ThreadController extends Controller
{
    /*
     * @function addThreadByContestIDAndProblemID
     * @input $request, $contest_id, $problem_id
     *
     * @return Redirect
     * @description add new thread for problem
     */
    public function addThreadByContestIDAndProblemID(Request $request, $contest_id, $problem_id)
    {
        $data = [];
        /* We need to validate some input */
        $input = $request->input();
        $vdtor = Validator::make($input, [
            'content' => "required | min:20 | max:1000"
        ]);
        if($vdtor->fails())
        {
            return Redirect::to('discuss/'.$contest_id.'/'.$problem_id)->withErrors($vdtor)->withInput($data)->with([
                "content" => $input['content']
            ]);
        }
        $author_id = $request->session()->get('uid');
        $threads = Thread::where("author_id",$author_id);
        if(!$threads->get()->isEmpty())
        {
            $last_send_time = Thread::find($threads->max('id'))->created_at;
            $now = Carbon::now();
            $time_interval = $now->diffInSeconds($last_send_time);
        }
        else
        {
            /* When this is the first thread this user post set $time_interval bigger than 10sec to pass the interval check */
            $time_interval = 11;
        }
        if($time_interval < 10)
        {
            return Redirect::to('discuss/'.$contest_id.'/'.$problem_id)->with("info",
                "Don't submit twice in 10 seconds!"
            );
        }
        else
        {
            $threadObj = new Thread;
            $threadObj->author_id = $author_id;
            $threadObj->cid = $contest_id;
            $threadObj->pid = $problem_id;
            $threadObj->content = $input['content'];
            $threadObj->save();
        }
        return Redirect::to('discuss/'.$contest_id.'/'.$problem_id);
    }

    /*
     * @function getThreadByContestIDAndProblemID
     * @input $request, $contest_id, $problem_id
     *
     * @return View
     * @description get thread list of the problem
     */
    public function getThreadByContestIDAndProblemID(Request $request, $contest_id, $problem_id)
    {
        $data = [];

        $data['contest_id'] = $contest_id;
        $data['problem_id'] = $problem_id;
        $threadObj = Thread::where([
            'cid' => $contest_id,
            'pid' => $problem_id
        ])->orderby('id', 'asc')->get();
        $count=0;
        foreach($threadObj as $thread)
        {
            //var_dump($thread->info);
            $data['threads'][$count] = $thread;
            $count++;
        }
        return View::make("discuss.index",$data);
    }

    /*
     * @function getThreadByThreadID
     * @input $thread_id
     *
     * @return thread_obj
     * @description get thread by thread_id
     */
    public function getThreadByThreadID(Request $request, $thread_id)
    {
        $data = [];

        $threadObj = Thread::find($thread_id);
        $data['thread'] = $threadObj;
        return $data;
    }

    /*
     * @function deleteThreadByThreadID
     * @input $request, $thread_id
     *
     * @return Redirect
     * @description delete thread by thread_id (only admin permitted)
     */
    public function deleteThreadByThreadID(Request $request, $thread_id)
    {
        $thread = Thread::find($thread_id);
        if($thread != NULL)
        {
            $contest_id = $thread->cid;
            $problem_id = $thread->pid;
            $uid = $request->session()->get('uid');
            $deleteContent = $thread->content;
            OJLog::deleteDiscuss($uid, $contest_id, $problem_id, $deleteContent);
            Thread::where('id',$thread_id)->delete();
            return Redirect::to('/discuss/'.$contest_id.'/'.$problem_id);
        }
        return Redirect::to('/discuss/0');
    }

    /*
     * @function getThreadByContestID
     * @input $contest_id
     *
     * @return Redirect
     * @description redirect to /discuss/$contest_id/p/1
     */
    public function getThreadByContestID(Request $request, $contest_id)
    {
        return Redirect::to("/discuss/$contest_id/p/1");
    }

    /*
     * @function getThreadByContestIDAndPageID
     * @input $contest_id, $page_id
     *
     * @return View
     * @description get threads list by $contest_id and $page_id
     */
    public function getThreadByContestIDAndPageID(Request $request, $contest_id, $page_id)
    {
        $data = [];
        $itemsPerPage = 5;

        $data['contest_id'] = $contest_id;
        $threads = Thread::where('cid', $contest_id)->orderby('id', 'desc')->get();
        $threads_num = $threads->count();
        $data["page_num"] = (int)($threads_num / $itemsPerPage + ($threads_num % $itemsPerPage == 0 ? 0 : 1));
        $data['page_id'] = $page_id;
        if($page_id > $data["page_num"])
            $page_id = $data["page_num"];
        if($page_id <= 0)
            $page_id = 1;
        for($count = 0, $i = ($page_id - 1) * $itemsPerPage; $count < $itemsPerPage && $i < $threads->count(); $i++, $count++)
        {
            $data['threads'][$count] = $threads[$i];
        }
        return View::make("discuss.list",$data);
    }
}
