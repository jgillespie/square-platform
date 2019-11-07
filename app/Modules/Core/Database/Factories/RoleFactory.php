<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Modules\Core\Models\Role;
use Faker\Generator as Faker;

$factory->define(Role::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->word,
        'description' => $faker->sentence,
    ];
});
