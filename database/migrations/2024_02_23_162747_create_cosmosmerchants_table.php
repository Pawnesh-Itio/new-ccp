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
        Schema::create('cosmosmerchants', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mid', 250);
            $table->string('pgmerchentId', 255);
            $table->string('complianceStatus', 255);
            $table->string('merchentLegalName', 255);
            $table->string('businessName', 255);
            $table->string('locationCountry', 255);
            $table->string('Address', 255);
            $table->string('City', 255);
            $table->string('State', 255);
            $table->string('postalCode', 255);
            $table->string('categoryofMerchant', 255);
            $table->string('Purpose', 255)->nullable();
            $table->string('merchantIntegrationApproach', 255)->nullable();
            $table->string('panNo', 255)->nullable();
            $table->string('mebusinessType', 255);
            $table->string('mcc', 255)->nullable();
            $table->string('settlementType', 255);
            $table->string('perDaytransactionCnt', 255);
            $table->string('perdaytransactionlimit', 255);
            $table->string('pertransactionLimit', 255);
            $table->string('user_id', 255)->index('user_id');
            $table->string('whitelistedURL', 255)->nullable();
            $table->string('externalMID', 255)->nullable();
            $table->string('externalTID', 255)->nullable();
            $table->string('gstn', 255);
            $table->string('merchantType', 255)->nullable();
            $table->string('merchantGenre', 255)->nullable();
            $table->string('onboardingType', 255)->nullable();
            $table->string('mobileNumber', 255);
            $table->string('WebApp', 255)->nullable();
            $table->string('WebURL', 255);
            $table->string('vpa', 255)->nullable();
            $table->string('sid', 255)->nullable();
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
        Schema::dropIfExists('cosmosmerchants');
    }
};
