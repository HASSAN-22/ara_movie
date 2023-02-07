<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScreenShotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('screen_shots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movie_link_id');
            $table->string('screen_shot');
            $table->timestamps();
            $table->foreign('movie_link_id')->references('id')->on('movie_links')->onDelete('cascade')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('screen_shots');
    }
}
