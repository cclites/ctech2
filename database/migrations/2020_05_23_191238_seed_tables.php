<?php

use Faker\Generator as Faker;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SeedTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $faker = new Faker();
        $counter = 0;

        factory(App\Project::class, 2)
            ->create()
            ->each(function($project) use(&$counter){
                factory(App\Task::class, 8)
                    ->create([
                        'project_id'=>$project->id,
                    ]);
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \App\Task::query()->delete();
        \App\Project::query()->delete();
    }
}
