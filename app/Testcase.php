<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Testcase extends Model
{
    protected $table = "testcases";
    protected $fillable = ["pid", "rank", "score","input_file_name", "output_file_name", "md5sum_input", "md5sum_output"];
}
