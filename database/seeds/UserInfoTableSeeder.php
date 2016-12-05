<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Userinfo;

class UserInfoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userinfos = [
            [
                "realname" => "VOID001",
                "nickname" => "VOID001",
                "school" => "NEU",
                "stu_id" => 1,
                "uid" => 1,
                "submit_count" => 100,
                "ac_count" => 100
            ],
            [
                "realname" => "admin",
                "nickname" => "admin",
                "school" => "NEU",
                "stu_id" => 2,
                "uid" => 2,
                "submit_count" => 100,
                "ac_count" => 100
            ]
        ];

        for ($i = 0; $i < 100; $i++) {
            $userinfos[$i + 2] = [
                "realname" => "testUser" . $i,
                "nickname" => "testUser" . $i,
                "school" => "NEU",
                "stu_id" => $i + 3,
                "uid" => $i + 3,
                "submit_count" => $i + 100,
                "ac_count" => $i + 3
            ];
        }

        foreach ($userinfos as $userinfo) {
            Userinfo::create($userinfo);
        }
    }
}
