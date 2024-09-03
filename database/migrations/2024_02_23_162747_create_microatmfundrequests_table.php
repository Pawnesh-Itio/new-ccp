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
        Schema::create('microatmfundrequests', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 250)->nullable();
            $table->string('bank', 250)->nullable();
            $table->string('account', 250)->nullable();
            $table->string('ifsc', 250)->nullable();
            $table->string('apitxnid', 250)->nullable();
            $table->enum('pay_type', ['manual', 'payout'])->nullable();
            $table->enum('mode', ['IMPS', 'NEFT'])->default('IMPS');
            $table->string('payoutid', 250)->nullable();
            $table->string('payoutref', 250)->nullable();
            $table->double('amount', 11, 2)->default(0);
            $table->longText('remark')->nullable();
            $table->enum('status', ['approved', 'pending', 'rejected'])->default('pending');
            $table->enum('type', ['matmwallet', 'matmbank'])->nullable();
            $table->integer('user_id')->nullable();
            $table->timestamp('create_time')->nullable()->unique();
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
        Schema::dropIfExists('microatmfundrequests');
    }
};
