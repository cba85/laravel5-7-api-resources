<?php

use Faker\Generator as Faker;

$factory->define(App\Topic::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'user_id' => 1,
    ];
});
