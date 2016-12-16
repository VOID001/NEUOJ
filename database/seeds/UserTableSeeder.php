<?php


use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\User;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends DatabaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                "username" => "VOID001",
                "gid" => 1,
                "password" => Hash::make('1234567'),
                "email" => "zhangjianqiu_133@yeah.net"
            ],
            [
                "username" => "admin",
                "gid" => 1,
                "password" => Hash::make("admin"),
                "email" => "admin@noreply.com"
            ]
        ];

        for ($i = 0; $i < 100; $i++) {
            $users[$i + 2] = [
                "username" => "testUser" . $i,
                "password" => Hash::make("test"),
                "email" => "test" . $i . "@noreply.com"
            ];
        }

        foreach($users as $user)
        {
            User::create($user);
        }
    }
}
