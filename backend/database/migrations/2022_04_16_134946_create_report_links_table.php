<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movie_id');
            $table->foreignId('movie_link_id');
            $table->foreignId('link_id');
            $table->text('description');
            $table->timestamps();
            $table->foreign('movie_id')->references('id')->on('movies')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('movie_link_id')->references('id')->on('movie_links')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('link_id')->references('id')->on('links')->onDelete('cascade')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('report_links');
    }
}
