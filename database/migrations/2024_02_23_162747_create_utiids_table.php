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
        Schema::create('utiids', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('vleid')->unique();
            $table->string('payid', 250)->nullable();
            $table->string('txnid', 250)->nullable();
            $table->string('vlepassword')->nullable();
            $table->string('name');
            $table->string('location');
            $table->string('contact_person');
            $table->string('pincode');
            $table->string('state');
            $table->string('email');
            $table->string('mobile');
            $table->enum('type', ['new', 'reset'])->default('new');
            $table->integer('user_id');
            $table->integer('api_id')->nullable();
            $table->integer('sender_id')->nullable();
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');
            $table->longText('remark')->nullable();
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
        Schema::dropIfExists('utiids');
    }
};
