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
        Schema::create('callbackresponses', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('url')->nullable();
            $table->longText('response');
            $table->string('status');
            $table->string('product');
            $table->integer('transaction_id');
            $table->integer('user_id');
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
        Schema::dropIfExists('callbackresponses');
    }
};
