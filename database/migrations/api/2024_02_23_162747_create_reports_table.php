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
        Schema::connection('pgsql_second')->create('reports', function (Blueprint $table) {
            $table->increments('id');
            $table->string('number')->nullable()->index('number');
            $table->string('mobile', 11)->nullable();
            $table->integer('provider_id');
            $table->integer('api_id');
            $table->double('amount', 11, 2)->default(0);
            $table->double('charge', 11, 2)->default(0);
            $table->double('profit', 11, 2)->default(0);
            $table->double('gst', 11, 2)->default(0);
            $table->double('tds', 11, 2)->default(0);
            $table->string('apitxnid')->nullable()->index('apitxnid');
            $table->string('txnid')->nullable()->index('txnid');
            $table->string('payid')->nullable();
            $table->string('refno')->nullable();
            $table->longText('description')->nullable();
            $table->longText('remark')->nullable();
            $table->longText('option1')->nullable();
            $table->string('option2')->nullable();
            $table->string('option3')->nullable();
            $table->string('option4')->nullable();
            $table->enum('status', ['pending', 'success', 'failed', 'reversed', 'refunded', 'complete', 'initiated', 'authorised', 'declined', 'partial capture', 'partial refund'])->default('pending')->index('status');
            $table->integer('user_id')->index();
            $table->integer('credit_by')->nullable();
            $table->enum('rtype', ['main', 'commission'])->default('main');
            $table->enum('via', ['api', 'portal', 'app'])->default('portal');
            $table->double('adminprofit', 11, 2)->default(0);
            $table->double('balance', 11, 2)->default(0);
            $table->enum('trans_type', ['credit', 'debit', 'none'])->default('none');
            $table->string('product',50); 
            $table->string('aadhar', 225)->nullable();
            $table->string('bank', 225)->nullable();
            $table->string('mytxnid', 225)->nullable();
            $table->string('terminalid', 225)->nullable();
            $table->string('authcode', 225)->nullable();
            $table->enum('transtype', ['fund', 'transaction'])->default('transaction');
            $table->enum('aepstype', ['MS', 'BE', 'CW', 'matm', 'AP', 'UPI', 'upicollect', 'payout', 'card'])->default('MS')->index('aepstype');
            $table->enum('TxnMedium', ['1', '2'])->nullable();
            $table->integer('credited_by')->nullable();
            $table->string('payeeVPA', 225)->nullable();
            $table->string('payer_vpa', 225)->nullable();
            $table->string('payerMobile', 225)->nullable();
            $table->string('payerAccName', 225)->nullable();
            $table->string('payerIFSC', 225)->nullable();
            $table->integer('wid')->nullable();
            $table->double('wprofit', 11, 2)->default(0);
            $table->integer('mdid')->nullable();
            $table->double('mdprofit', 11, 2)->default(0);
            $table->integer('disid')->nullable();
            $table->double('disprofit', 11, 2)->default(0);
            $table->integer('oid')->nullable();
            $table->double('oprofit', 11, 2)->default(0);
            $table->timestamp('created_at')->nullable()->index('created_at');
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('pgsql_second')->dropIfExists('reports');
    }
};
