<?php

use Illuminate\Database\Seeder;
use App\Contest;
use App\ContestProblem;
use App\ContestUser;

class ContestTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $contest_infos[] = [];
        for ($i = 0; $i < 100; $i++) {
            switch ($i % 3) {
                case 0:
                    $contest_infos[$i] = [
                        "contest_id" => $i + 1,
                        "contest_name" => "testContest" . ($i + 1),
                        "begin_time" => date('Y-m-d H:i:s'),
                        "end_time" => date('Y-m-d H:i:s', time() + 14400),
                        "admin_id" => 2,
                        "contest_type" => 0
                    ];
                    break;
                case 1:
                    $contest_infos[$i] = [
                        "contest_id" => $i + 1,
                        "contest_name" => "testContest" . ($i + 1),
                        "begin_time" => date('Y-m-d H:i:s'),
                        "end_time" => date('Y-m-d H:i:s', time() + 14400),
                        "admin_id" => 2,
                        "contest_type" => 1
                    ];
                    $contest_user = [
                        "contest_id" => $i + 1,
                        "user_id" => 2,
                        "username" => "admin"
                    ];
                    ContestUser::create($contest_user);
                    break;
                case 2:
                    $contest_infos[$i] = [
                        "contest_id" => $i + 1,
                        "contest_name" => "testContest" . ($i + 1),
                        "begin_time" => date('Y-m-d H:i:s', strtotime("+1 day")),
                        "end_time" => date('Y-m-d H:i:s', strtotime("+1 day") + 14400),
                        "register_begin_time" => date('Y-m-d H:i:s', strtotime("last month")),
                        "register_end_time" => date('Y-m-d H:i:s', time() + 14400),
                        "admin_id" => 2,
                        "contest_type" => 2
                    ];
                    break;
            }
            $contest_problem = [
                "contest_id" => $i + 1,
                "problem_id" => $i + 1,
                "contest_problem_id" => 1,
                "problem_title" => "A"
            ];
            ContestProblem::create($contest_problem);
        }
        foreach ($contest_infos as $contest_info) {
            Contest::create($contest_info);
        }
    }
}
