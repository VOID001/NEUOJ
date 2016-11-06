<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ContestPageVisited extends Event
{
    use SerializesModels;

    public $contest_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($contest_id)
    {
        $this->contest_id = $contest_id;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
