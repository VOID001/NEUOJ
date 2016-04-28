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
        'gid' => $faker->numberBetween(0, 5),
        'email' => $faker->email,
        'password' => bcrypt(str_random(10)),
    ];
});

$factory->defineAs(App\User::class, 'admin', function(){
    return [
        'username' => "admin",
        'email' => "admin@noreply.com",
        'password' => bcrypt("admin"),
    ];
});
