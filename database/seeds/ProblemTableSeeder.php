<?php

use Illuminate\Database\Seeder;
use App\Problem;

class ProblemTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $problems = [
            ["title" => "A", "description" => "AAAA", "visibility_locks" => 3, "time_limit" => 3, "mem_limit" => 104, "output_limit" => 10000000, "difficulty" => 1, "author_id" => 1, ],
            ["title" => "B", "description" => "A2AA", "visibility_locks" => 0, "time_limit" => 2, "mem_limit" => 1024, "output_limit" => 10000000, "difficulty" => 1, "author_id" => 1, ],
            ["title" => "C", "description" => "AA4A", "visibility_locks" => 1, "time_limit" => 3, "mem_limit" => 1024, "output_limit" => 10000000, "difficulty" => 1, "author_id" => 1, ],
            ["title" => "D", "is_spj" => 1, "description" => "A1AA", "visibility_locks" => 0, "time_limit" => 3, "mem_limit" => 1024, "output_limit" => 10000000, "difficulty" => 1, "author_id" => 1, ],
            ["title" => "E", "description" => "1AAA", "visibility_locks" => 0, "time_limit" => 3, "mem_limit" => 1024, "output_limit" => 10000000, "difficulty" => 1, "author_id" => 1, ],
            ["title" => "F", "description" => "A23A", "visibility_locks" => 2, "time_limit" => 1, "mem_limit" => 14, "output_limit" => 10000000, "difficulty" => 1, "author_id" => 1, ],
            ["title" => "G", "description" => "ArAA", "visibility_locks" => 0, "time_limit" => 3, "mem_limit" => 1024, "output_limit" => 10000000, "difficulty" => 1, "author_id" => 1, ],
            ["title" => "H", "description" => "ArrA", "visibility_locks" => 0, "time_limit" => 1, "mem_limit" => 1024, "output_limit" => 10000000, "difficulty" => 1, "author_id" => 1, ],
            ["title" => "I", "description" => "AArr", "visibility_locks" => 0, "time_limit" => 3, "mem_limit" => 1, "output_limit" => 10000000, "difficulty" => 1, "author_id" => 1, ],
            ["title" => "J", "description" => "rrAA", "visibility_locks" => 0, "time_limit" => 3, "mem_limit" => 65536, "output_limit" => 10000000, "difficulty" => 1, "author_id" => 2, ],
            ["title" => "K", "description" => "rAAr", "visibility_locks" => 0, "time_limit" => 3, "mem_limit" => 1024, "output_limit" => 10000000, "difficulty" => 1, "author_id" => 2, ],
            ["title" => "L", "description" => "Arrr", "visibility_locks" => 0, "time_limit" => 5, "mem_limit" => 1024, "output_limit" => 10000000, "difficulty" => 1, "author_id" => 2, ],
            ["title" => "M", "description" => "rrrA", "visibility_locks" => 1, "time_limit" => 3, "mem_limit" => 1024, "output_limit" => 10000000, "difficulty" => 1, "author_id" => 2, ],
            ["title" => "N", "description" => "Arrr", "visibility_locks" => 0, "time_limit" => 3, "mem_limit" => 1024, "output_limit" => 10000000, "difficulty" => 1, "author_id" => 2, ],
            ["title" => "O", "description" => "rArA", "visibility_locks" => 7, "time_limit" => 3, "mem_limit" => 1024, "output_limit" => 10000000, "difficulty" => 1, "author_id" => 2, ],
            ["title" => "Hello World", "description" => "Just Ouput\n 'Hello World' ", "visibility_locks" => 0, "time_limit" => 3, "mem_limit" => 1024, "output_limit" => 10000000, "difficulty" => 1, "author_id" => 2, ],
        ];

        foreach($problems as $problem)
        {
            Problem::create($problem);
        }
    }
}
