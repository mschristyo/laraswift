<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

$factory->define(Role::class, function (Faker $faker) {
    return [
        'name' => Str::random(7),
        'removable' => 0,
    ];
});
