<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(UserTableSeeder::class);
        $this->call(UserInfoTableSeeder::class);
        $this->call(ExecutableTableSeeder::class);

        if(env('APP_ENV') == "local")
        {
            $this->call(ProblemTableSeeder::class);
            $this->call(SubmissionTableSeeder::class);
            $this->call(ContestTableSeeder::class);
            $this->call(TrainTableSeeder::class);
            $this->call(TestcasesTableSeeder::class);
            $this->call(ThreadTableSeeder::class);
        }
        Model::reguard();
    }
}
