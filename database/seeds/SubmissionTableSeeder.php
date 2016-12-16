<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Submission;

class SubmissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 100; $i++) {
            $submissions[$i] = [
                "runid" => $i + 1,
                "pid" => $i + 1,
                "uid" => ($i + 1) % 5 + 1,
                "exec_time" => 0,
                "exec_mem" => 0,
                "lang" => (($i + 1) % 5) ? "C" : "C++",
                "submit_time" => date('Y-m-d H:i:s')
            ];
        }

        for ($i = 0; $i < 100; $i++) {
            switch ($i % 10) {
                case 0:
                case 1:
                case 2:
                case 3:
                case 4:
                case 5:
                    $submissions[$i]["result"] = "Accepted";
                    break;
                case 6:
                    $submissions[$i]["result"] = "Wrong Answer";
                    break;
                case 7;
                    $submissions[$i]["result"] = "Time Limit Exceed";
                    break;
                case 8:
                    $submissions[$i]["result"] = "Runtime Error";
                    break;
                case 9:
                    $submissions[$i]["result"] = "Compile Error";
                    break;
            }
        }

        foreach ($submissions as $submission) {
            Submission::create($submission);
        }
    }
}
