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
        Schema::create('apis', function (Blueprint $table) {
            $table->increments('id');
            $table->string('product');
            $table->string('name');
            $table->longText('url')->nullable();
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->longText('optional1')->nullable();
            $table->string('optional2', 250)->nullable();
            $table->string('optional3', 255)->nullable();
            $table->string('code')->index('code');
            $table->enum('type', ['recharge', 'bill', 'money', 'pancard', 'fund', 'payment']);
            $table->enum('status', ['0', '1'])->default('1');
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
        Schema::dropIfExists('apis');
    }
};
