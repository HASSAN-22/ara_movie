<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVipTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vip_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('cart');
            $table->string('plan');
            $table->integer('price');
            $table->integer('discount');
            $table->string('transaction_id');
            $table->enum('type',['wallet','bank']);
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
        Schema::dropIfExists('vip_transactions');
    }
}
