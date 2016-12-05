<?php

use Illuminate\Database\Seeder;
use App\Thread;

class ThreadTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $threadinfos[] = [];
        for ($i = 0; $i < 100; $i++) {
            $threadinfos[$i] = [
                "cid" => 0,
                "pid" => $i + 1,
                "author_id" => 2,
                "content" => "testThread,lalalalalalala\n\n" . $i,
            ];
        }

        foreach ($threadinfos as $threadinfo) {
            Thread::create($threadinfo);
        }

        for ($i = 0; $i < 100; $i++) {
            for ($j = 0; $j < 20; $j++) {
                $threadinfo = [
                    "cid" => $i + 1,
                    "pid" => 1,
                    "author_id" => 2,
                    "content" => "testThread,lalalalalalala\n\n" . $j,
                ];
                Thread::create($threadinfo);
            }

        }


    }
}
