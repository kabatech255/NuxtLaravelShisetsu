<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Summary;
use Faker\Generator as Faker;

$factory->define(Summary::class, function (Faker $faker) {
  return [
    'description' => $faker->text(140)
  ];
});
