<?php

namespace App\Http\Controllers;

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
        if($threads != NULL)
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

        $data['contest_id']=$contest_id;
        $data['problem_id']=$problem_id;
        $threadObj = Thread::where([
            'cid' => $contest_id,
            'pid' => $problem_id
        ])->orderby('id', 'asc')->get();
        $count=0;
        foreach($threadObj as $thread)
        {
            $data['threads'][$count]=$thread;
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
        $data['thread']=$threadObj;
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
        $thread=Thread::find($thread_id);
        if($thread != NULL)
        {
            $contest_id=$thread->cid;
            $problem_id=$thread->pid;
            Thread::where('id',$thread_id)->delete();
            return Redirect::to('/discuss/'.$contest_id.'/'.$problem_id);
        }
        return Redirect::to('/discuss');
    }
}
