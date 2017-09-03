<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Hippodrome;

class CreateTableHyppodrome extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hippodromes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('public_id', 20)->unique();
            $table->string('name', 40);
            $table->string('status', 15)->default(Hippodrome::STATUS_ACTIVE);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hippodromes');
    }
}
