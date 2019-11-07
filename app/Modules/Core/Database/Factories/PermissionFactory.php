<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Modules\Core\Models\Permission;
use Faker\Generator as Faker;

$factory->define(Permission::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->word,
        'description' => $faker->sentence,
    ];
});
