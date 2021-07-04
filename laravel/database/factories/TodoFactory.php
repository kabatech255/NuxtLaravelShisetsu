<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Todo;
use App\Models\Examinator;
use Faker\Generator as Faker;

$factory->define(Todo::class, function (Faker $faker) {
  return [
    'employee_id' => config('app.test_id'),
    'body' => $faker->text(30),
  ];
});
