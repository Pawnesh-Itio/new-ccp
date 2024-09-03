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
        Schema::create('xettlemerchantbcks', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('merchantBusinessName', 225)->nullable();
            $table->string('merchantVirtualAddress', 225)->nullable();
            $table->string('requestUrl', 225)->nullable();
            $table->string('panNo', 225)->nullable();
            $table->string('contactEmail', 225)->nullable();
            $table->string('gstn', 225)->nullable();
            $table->string('merchantBusinessType', 225)->nullable();
            $table->string('perDayTxnCount', 225)->nullable();
            $table->string('perDayTxnLmt', 225)->nullable();
            $table->string('perDayTxnAmt', 225)->nullable();
            $table->string('mobile', 225)->nullable();
            $table->string('address', 225)->nullable();
            $table->string('state', 225)->nullable();
            $table->string('city', 225)->nullable();
            $table->string('pinCode', 225)->nullable();
            $table->string('mcc', 225)->nullable();
            $table->string('vpaaddress', 225)->nullable();
            $table->string('subMerchantId', 225)->nullable();
            $table->string('contact_id', 225)->nullable();
            $table->string('f_name', 225)->nullable();
            $table->string('l_name', 225)->nullable();
            $table->string('payout_mobile', 225)->nullable();
            $table->string('payout_email', 225)->nullable();
            $table->string('account', 225)->nullable();
            $table->string('ifsc', 225)->nullable();
            $table->string('user_id', 225);
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
        Schema::dropIfExists('xettlemerchantbcks');
    }
};
