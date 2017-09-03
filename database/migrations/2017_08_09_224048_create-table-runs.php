<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Horse;

class CreateTableRuns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('runs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('public_id', 20)->unique();
            $table->dateTime('date');
            $table->string('status', 15);
            $table->integer('hippodrome_id')->unsigned();
            $table->foreign('hippodrome_id')->references('id')->on('hippodromes');
            $table->timestamps();
        });

        Schema::create('run_horse', function (Blueprint $table) {
            $table->integer('run_id')->unsigned();
            $table->integer('horse_id')->unsigned();
            $table->foreign('run_id')->references('id')->on('runs');
            $table->foreign('horse_id')->references('id')->on('horses');
            $table->primary(['run_id', 'horse_id']);
            $table->string('status', 15)->default(Horse::STATUS_ACTIVE);
            $table->boolean('isGain')->default(false);
            $table->float('static_table');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('run_horse');
        Schema::dropIfExists('runs');
    }
}
