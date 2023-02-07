<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoviesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id');
            $table->string('short_link');
            $table->string('title')->unique();
            $table->string('fa_title')->unique();
            $table->enum('type',['serial','movie']);
            $table->string('image');
            $table->string('awards');
            $table->string('imdbId');
            $table->text('description');
            $table->text('moreDescription')->nullable();
            $table->string('quality')->nullable();
            $table->string('genre')->nullable();
            $table->string('product')->nullable();
            $table->string('lang')->nullable();
            $table->string('published_at')->nullable();
            $table->string('time')->nullable();
            $table->string('director')->nullable();
            $table->text('actor')->nullable();
            $table->text('keyword')->nullable();
            $table->string('imdb')->nullable();
            $table->string('critics')->nullable();
            $table->string('rank')->nullable();
            $table->string('pg')->nullable();
            $table->enum('play_status',['yes','no'])->nullable();
            $table->string('broadcast_day')->nullable();
            $table->string('network')->nullable();
            $table->string('caption_movie')->nullable();
            $table->enum('subtitle',['yes','no']);
            $table->enum('dubbed',['yes','no']);
            $table->enum('status',['yes','no']);
            $table->enum('status_comment',['yes','no']);
            $table->enum('soon',['yes','no']);
            $table->string('slug');
            $table->timestamps();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movies');
    }
}
