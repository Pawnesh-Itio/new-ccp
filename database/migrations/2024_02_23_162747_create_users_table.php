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
        Schema::create('users', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('agentcode', 250)->nullable();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('mobile', 10)->unique();
            $table->string('password')->nullable();
            $table->string('remember_token')->nullable();
            $table->string('otpverify', 250)->default('no');
            $table->integer('otpresend')->default(0);
            $table->double('mainwallet', 11, 2)->default(0);
            $table->double('microatmbalance', 11, 2)->nullable()->default(0);
            $table->double('nsdlwallet', 11, 2)->default(0);
            $table->double('aepsbalance', 11, 2)->default(0);
            $table->enum('gstrate', ['18', '28'])->default('18');
            $table->double('lockedamount', 11, 2)->default(0);
            $table->integer('role_id');
            $table->integer('parent_id')->default(0);
            $table->integer('company_id')->nullable();
            $table->integer('scheme_id')->nullable();
            $table->enum('status', ['active', 'block','onboarding'])->default('active');
            $table->longText('address')->nullable();
            $table->string('gstin')->nullable();
            $table->string('gender', 20)->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('pincode', 6)->nullable();
            $table->string('pancard')->nullable();
            $table->string('aadharcard', 12)->nullable();
            $table->longText('pancardpic')->nullable();
            $table->longText('aadharcardpic')->nullable();
            $table->longText('gstpic')->nullable();
            $table->longText('profile')->nullable();
            $table->enum('kyc', ['pending', 'submitted', 'verified', 'rejected'])->default('pending');
            $table->longText('callbackurl')->nullable();
            $table->string('sdktoken', 255)->nullable();
            $table->longText('remark')->nullable();
            $table->enum('resetpwd', ['default', 'changed'])->default('default');
            $table->string('account', 250)->nullable();
            $table->string('bank', 250)->nullable();
            $table->string('ifsc', 250)->nullable();
            $table->string('contact_id1', 225)->nullable();
            $table->string('account2', 250)->nullable();
            $table->string('bank2', 250)->nullable();
            $table->string('ifsc2', 250)->nullable();
            $table->string('contact_id2', 225)->nullable();
            $table->string('account3', 250)->nullable();
            $table->string('bank3', 250)->nullable();
            $table->string('ifsc3', 250)->nullable();
            $table->string('contact_id3', 225)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->double('wstock', 11, 0)->default(0);
            $table->double('mstock', 11, 0)->default(0);
            $table->double('dstock', 11, 0)->default(0);
            $table->double('rstock', 11, 0)->default(0);
            $table->string('apptoken', 250)->nullable()->default('none');
            $table->string('bcid', 250)->nullable();
            $table->longText('qrdata')->nullable();
            $table->longText('qrData2')->nullable();
            $table->string('passwordold', 250)->nullable();
            $table->string('membership', 20)->default('free');
            $table->string('rrn', 250)->nullable();
            $table->double('amount', 11, 2)->default(0);
            $table->enum('soundBoxLanguage', ['HINDI', 'ENGLISH'])->default('HINDI');
            $table->enum('soundBoxType', ['QS1', 'QS2'])->default('QS1');
            $table->string('soundBoxSerial', 200)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
