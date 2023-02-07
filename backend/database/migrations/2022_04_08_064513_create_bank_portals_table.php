<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankPortalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_portals', function (Blueprint $table) {
            $table->id();
            $table->string('bank_name');
            $table->string('code_id');
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->enum('status',['yes','no']);
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
        Schema::dropIfExists('bank_portals');
    }
}
