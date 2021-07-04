<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Shop;
use Faker\Generator as Faker;
use App\Models\User;

$factory->define(Shop::class, function (Faker $faker) {
  $admin = User::first();
  $storeName = $faker->city;
  return [
    'store_code' => $faker->unique()->numberBetween(10001, 10035),
    'branch_code' => $faker->randomNumber(2),
    'name' => str_replace(mb_substr($storeName, -1, 1), '店', $storeName),
    'created_by' => $admin->login_id
  ];
});
