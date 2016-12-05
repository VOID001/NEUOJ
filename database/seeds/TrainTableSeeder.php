<?php

use Illuminate\Database\Seeder;
use App\Train;
use App\TrainProblem;


class TrainTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $train_infos[] = [];
        for ($i = 0; $i < 100; $i++) {
            $train_infos[$i] = [
                "train_id" => $i + 1,
                "train_name" => "testTrain" . ($i + 1),
                "description" => "testTrain" . ($i + 1),
                "train_chapter" => 5,
                "auth_id" => 2,
                "train_type" => 0
            ];
            for ($j = 0; $j < 5; $j++) {
                for ($k = 0; $k < 5; $k++) {
                    $train_problem = [
                        "train_id" => $i + 1,
                        "chapter_id" => $j + 1,
                        "problem_id" => $k + 1 + $j,
                        "problem_title" => "testTrain",
                    ];
                    TrainProblem::create($train_problem);
                }
            }
        }

        foreach ($train_infos as $train_info) {
            Train::create($train_info);
        }
    }
}