<?php

use Illuminate\Database\Seeder;

use App\Testcase;

class TestcasesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dataTable = [
            [
                "pid" => 16,
                "rank" => 1,
                "input_file_name" => "helloworld.in",
                "output_file_name" => "helloworld.out",
                "md5sum_input" => "b026324c6904b2a9cb4b88d6d61c81d1",
                "md5sum_output" => "59ca0efa9f5633cb0371bbc0355478d8",
            ],
        ];

        foreach($dataTable as $data)
        {
            Testcase::create($data);
        }
    }
}
