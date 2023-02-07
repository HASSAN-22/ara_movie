<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfigSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('config_sites', function (Blueprint $table) {
            $table->id();
            $table->string('site_name');
            $table->string('email')->nullable();
            $table->string('mobile')->nullable();
            $table->string('phone')->nullable();
            $table->string('logo');
            $table->string('logo_mobile');
            $table->string('copy_right');
            $table->string('telegram')->nullable();
            $table->string('twitter')->nullable();
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('omdb_api')->nullable();
//            $table->string('captcha_key');
            $table->string('min_amount');
            $table->string('max_amount');
            $table->string('alert_link');
            $table->string('front_link');
            $table->string('bc_link');
            $table->text('alert');
            $table->text('description')->nullable();
            $table->eunm('about_us',['yes','no']);
            $table->eunm('contact_us',['yes','no']);
            $table->enum('page',['yes','no']);
            $table->enum('vip',['yes','no']);
//            $table->enum('captcha',['yes','no']);
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
        Schema::dropIfExists('config_sites');
    }
}
