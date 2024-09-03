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
        Schema::create('iserveuagents', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('firstName', 255)->nullable();
            $table->string('lastName', 255)->nullable();
            $table->string('companyName', 255)->nullable();
            $table->string('integrationType', 255)->nullable();
            $table->string('pan', 255)->nullable();
            $table->string('settleType', 255)->nullable();
            $table->string('shopName', 255)->nullable();
            $table->string('merchantVirtualAddress', 255)->nullable();
            $table->string('merchant_id', 255)->nullable();
            $table->string('merchantMobileNumber', 255)->nullable();
            $table->string('requestingUserName', 255)->nullable();
            $table->string('area', 255)->nullable();
            $table->string('pincode', 255)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('shopaddress', 255)->nullable();
            $table->string('shopstate', 255)->nullable();
            $table->string('shopcity', 255)->nullable();
            $table->string('shopdistrict', 255)->nullable();
            $table->string('shoparea', 255)->nullable();
            $table->string('shoppincode', 255)->nullable();
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');
            $table->integer('user_id');
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
        Schema::dropIfExists('iserveuagents');
    }
};
