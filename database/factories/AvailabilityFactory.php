<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Availability;
use Faker\Generator as Faker;

$factory->define(Availability::class, function (Faker $faker) {

    return [
        'availability' => $faker->numberBetween(0, 2),
    ];
});
