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
        Schema::create('initiateqrs', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('refid', 255);
            $table->string('upiid', 255);
            $table->string('amount', 255);
            $table->integer('user_id');
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('initiateqrs');
    }
};
