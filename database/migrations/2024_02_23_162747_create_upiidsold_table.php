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
        Schema::create('upiidsold', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('businessName', 250)->nullable();
            $table->string('vpaAddress', 250)->nullable();
            $table->string('mobile', 250)->nullable();
            $table->string('panNo', 250)->nullable();
            $table->string('contactEmail', 250)->nullable();
            $table->string('gstn', 250)->nullable();
            $table->string('bankAccountNo', 250)->nullable();
            $table->string('bankIfsc', 250)->nullable();
            $table->string('address', 250)->nullable();
            $table->string('country', 250)->nullable();
            $table->string('state', 250)->nullable();
            $table->string('city', 250)->nullable();
            $table->string('pinCode', 250)->nullable();
            $table->longText('requestUrl')->nullable();
            $table->string('serviceType', 250)->nullable();
            $table->string('status', 250)->nullable();
            $table->string('vpa1', 250)->nullable();
            $table->string('vpa2', 250)->nullable();
            $table->longText('vpa1Qr')->nullable();
            $table->longText('vpa2Qr')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('via', 20)->default('portal');
            $table->string('subMerchantId', 251)->nullable();
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
        Schema::dropIfExists('upiidsold');
    }
};
