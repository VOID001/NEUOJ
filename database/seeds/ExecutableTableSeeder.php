<?php

use Illuminate\Database\Seeder;

use App\Executable;

class ExecutableTableSeeder extends Seeder
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
                "execid" => "c",
                "md5sum" => "c76e6afa913a9fc827c42c2357f47a53",
            ],
            [
                "execid" => "compare",
                "md5sum" => "71306aae6e243f8a030ab1bd7d6b354b",
            ],
            [
                "execid" => "cpp",
                "md5sum" => "cf76014b4e27a6e25378055f53733a7a",
            ],
            [
                "execid" => "run",
                "md5sum" => "c2cb7864f2f7343d1ab5094b8fd40da4",
            ],
        ];

        foreach($dataTable as $data)
        {
            Executable::create($data);
        }
    }
}
