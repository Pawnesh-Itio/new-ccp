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
        Schema::create('contacts', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('firstName', 250)->nullable();
            $table->string('lastName', 250)->nullable();
            $table->string('email', 250)->nullable();
            $table->string('mobile', 12)->nullable();
            $table->string('accountNumber', 20)->nullable();
            $table->string('ifsc', 20)->nullable();
            $table->string('type', 250)->nullable();
            $table->string('accountType', 250)->nullable();
            $table->string('referenceId', 50)->nullable();
            $table->string('contactId', 250)->nullable();
            $table->integer('user_id')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contacts');
    }
};
