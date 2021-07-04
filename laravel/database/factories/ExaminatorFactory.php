<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Examinator;
use Faker\Generator as Faker;
use App\Models\User;

$factory->define(Examinator::class, function (Faker $faker) {
  $admin = User::first();
  return [
    'team_code' => 1,
    'name' => $faker->name,
    'employee_id' => $faker->unique()->randomNumber(6),
    'created_by' => $admin->login_id
  ];
});
