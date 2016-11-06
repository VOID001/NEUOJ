<?php

namespace App\Listeners;

use App\Events\ContestPageVisited;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Request;

class ContestPageVisitedEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ContestPageVisited  $event
     * @return void
     */
    public function handle(ContestPageVisited $event)
    {
        if(Request::session()->has('channel_list'))
        {
            if(!collect(Request::session()->get('channel_list'))->contains($event->contest_id))
                Request::session()->push('channel_list', $event->contest_id);
        }
        else
            Request::session()->push('channel_list', $event->contest_id);
    }
}
