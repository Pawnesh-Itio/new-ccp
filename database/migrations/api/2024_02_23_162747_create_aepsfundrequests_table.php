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
        Schema::connection('pgsql_second')->create('aepsfundrequests', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 250)->nullable();
            $table->string('bank', 250)->nullable();
            $table->string('account', 250)->nullable();
            $table->string('ifsc', 250)->nullable();
            $table->string('apitxnid', 250)->nullable();
            $table->enum('pay_type', ['manual', 'payout'])->nullable();
            $table->string('payoutid', 250)->nullable();
            $table->string('payoutref', 250)->nullable();
            $table->double('amount', 11, 2)->default(0);
            $table->longText('remark')->nullable();
            $table->enum('status', ['approved', 'pending', 'rejected', 'accepted', 'reversed'])->default('pending');
            $table->enum('type', ['wallet', 'bank'])->nullable();
            $table->enum('mode', ['IMPS', 'NEFT', 'manual', 'payout'])->default('NEFT');
            $table->integer('api_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('contact_id', 225)->nullable();
            $table->timestamp('create_time')->nullable()->unique('create_time');
            $table->string('microtime', 250)->nullable()->unique('microtime');
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
        Schema::connection('pgsql_second')->dropIfExists('aepsfundrequests');
    }
};
