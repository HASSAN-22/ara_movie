<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comment_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comment_id');
            $table->foreignId('parent_id');
            $table->string('name');
            $table->string('email');
            $table->text('answer');
            $table->enum('status',['yes','no']);
            $table->timestamps();
            $table->foreign('comment_id')->references('id')->on('comments')->onDelete('cascade')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comment_answers');
    }
}
