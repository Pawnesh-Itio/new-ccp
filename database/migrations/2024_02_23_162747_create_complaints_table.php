<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('product', ['recharge', 'billpay', 'dmt', 'aeps', 'utipancard', 'UPI']);
            $table->string('subject', 500)->nullable();
            $table->longText('description')->nullable();
            $table->longText('solution')->nullable();
            $table->integer('transaction_id');
            $table->enum('status', ['pending', 'resolved']);
            $table->integer('user_id');
            $table->integer('resolve_id')->nullable();
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
        Schema::dropIfExists('complaints');
    }
};
