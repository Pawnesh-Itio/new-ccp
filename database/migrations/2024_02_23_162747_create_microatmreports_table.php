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
        Schema::create('microatmreports', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('mobile', 11)->nullable();
            $table->string('aadhar', 250)->nullable();
            $table->string('txnid', 50)->nullable();
            $table->integer('api_id')->nullable();
            $table->double('amount', 11, 2)->default(0);
            $table->double('charge', 11, 2)->default(0);
            $table->string('bank', 250)->nullable();
            $table->string('payid', 250)->nullable();
            $table->string('refno', 250)->nullable();
            $table->string('mytxnid', 250)->nullable();
            $table->string('terminalid', 250)->nullable();
            $table->string('authcode', 250)->nullable();
            $table->double('balance', 11, 2)->default(0);
            $table->enum('status', ['initiated', 'success', 'pending', 'failed', 'complete'])->default('initiated');
            $table->enum('type', ['credit', 'debit', 'none'])->default('none');
            $table->longText('remark')->nullable();
            $table->enum('rtype', ['main', 'commission'])->default('main');
            $table->enum('transtype', ['fund', 'transaction'])->default('transaction');
            $table->enum('aepstype', ['MS', 'BE', 'CW', 'matm'])->default('MS');
            $table->enum('TxnMedium', ['1', '2'])->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('credited_by')->nullable();
            $table->enum('via', ['app', 'portal', 'api'])->default('portal');
            $table->string('apitxnid', 250)->nullable();
            $table->integer('provider_id')->nullable();
            $table->integer('wid')->nullable();
            $table->double('wprofit', 11, 2)->default(0);
            $table->integer('mdid')->nullable();
            $table->double('mdprofit', 11, 2)->default(0);
            $table->integer('disid')->nullable();
            $table->double('disprofit', 11, 2)->default(0);
            $table->string('matm', 250)->default('aeps');
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
        Schema::dropIfExists('microatmreports');
    }
};
