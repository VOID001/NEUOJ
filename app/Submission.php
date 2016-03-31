<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $table = "submissions";

    protected $primaryKey = "runid";

    protected $fillable = [""];

    public function sim()
    {
        return $this->hasOne('App\Sim', 'runid');
    }
}
