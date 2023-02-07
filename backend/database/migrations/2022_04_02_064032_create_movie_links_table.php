<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovieLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movie_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movie_id');
            $table->string('title');
            $table->enum('vip',['yes','no']);
            $table->enum('caption_movie',['en','fa','sub']);
            $table->timestamps();
            $table->foreign('movie_id')->references('id')->on('movies')->onDelete('cascade')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movie_links');
    }
}
