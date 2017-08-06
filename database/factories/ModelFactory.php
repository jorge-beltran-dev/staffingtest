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

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\RotaSlotStaff::class, function (Faker\Generator $faker) {
    return [
        'id' => $faker->numberBetween(1, 9999999),
        'rotaid' => 332,
        'daynumber' => $faker->numberBetween(0, 6),
        'staffid' => 1,
        'slottype' => $faker->randomElement(['dayoff', 'shift']),
        'starttime' => $faker->time(),
        'endtime' => $faker->time(),
        'workhours' => $faker->randomFloat(2, 4, 10),
        'premiumminutes' => $faker->numberBetween(0, 600),
        'roletypeid' => 2,
        'freemiumminutes' => $faker->numberBetween(0, 600),
        'seniorcashierminutes' => $faker->numberBetween(0, 600),
        'splitshifttimes' => '--:--*--:--',
    ];
});
