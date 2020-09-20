<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;

$factory->define(Permission::class, function (Faker $faker) {
    return [
        'name' => Str::random(7),
        'removable' => 0,
    ];
});
