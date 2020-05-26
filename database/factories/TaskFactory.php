<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Task;
use Faker\Generator as Faker;

$factory->define(Task::class, function (Faker $faker) {

    static $index = 0;

    return [
        'name' => $faker->word,
        'priority' => $index++,
        'project_id' => now(),
    ];
});
