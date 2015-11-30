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
                "password" => Hash::make('1234567'),
                "email" => "zhangjianqiu_133@yeah.net"
            ],
            [
                "username" => "admin",
                "password" => Hash::make("admin"),
                "email" => "admin@noreply.com"
            ]
        ];

        foreach($users as $user)
        {
            User::create($user);
        }
    }
}
