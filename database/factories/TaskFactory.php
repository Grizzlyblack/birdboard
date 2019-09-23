<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\ProjectTask;
use Faker\Generator as Faker;

$factory->define(ProjectTask::class, function (Faker $faker) {
    return [
        'body'=>$faker->sentence,
        'project_id'=>factory(\App\Project::class),
        'completed'=>false
    ];
});
