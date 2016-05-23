<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'username' => $faker->name,
        'gid' => 0,
        'email' => $faker->email,
        'password' => bcrypt(str_random(10)),
    ];
});

$factory->defineAs(App\User::class, 'admin', function(Faker\Generator $faker){
    return [
        'username' => $faker->name,
        'gid' => 1,
        'email' => $faker->email,
        'password' => bcrypt("admin"),
    ];
});

$factory->defineAs(App\User::class, 'teacher', function(){
    return [
        'username' => "teacher",
        'email' => "teacher@noreply.com",
        'gid' => 2,
        'password' => bcrypt("teacher"),
    ];
});

$factory->define(App\Problem::class, function(Faker\Generator $faker){
    return [
        "title" => $faker->name,
        "description" => str_random(50),
        "visibility_locks" => 2,
        "time_limit" => 3,
        "mem_limit" => 104,
        "output_limit" => 1000,
        "difficulty" => 1,
        "author_id" => 1,
    ];
});
