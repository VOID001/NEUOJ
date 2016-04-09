<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContestBalloonEvent extends Model
{
    protected $table = "contest_balloon_events";
    protected $fillable = ["send_status"];
}
