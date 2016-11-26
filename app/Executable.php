<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Executable extends Model
{
    protected $table = "executables";

    protected $fillable = ["execid", "md5sum", "type"];
}
