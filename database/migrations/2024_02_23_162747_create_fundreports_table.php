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
        Schema::create('fundreports', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('type', ['transfer', 'request', 'return'])->nullable();
            $table->string('paymode', 20)->nullable()->index('loadcashes_pmethod_id_foreign');
            $table->unsignedInteger('fundbank_id')->index('loadcashes_netbank_id_foreign');
            $table->double('amount');
            $table->string('ref_no', 255);
            $table->string('paydate', 30)->nullable();
            $table->enum('status', ['success', 'pending', 'failed', 'approved', 'rejected'])->nullable()->default('pending')->index('loadcashes_status_id_foreign');
            $table->unsignedInteger('user_id')->index('loadcashes_user_id_foreign');
            $table->integer('credited_by')->nullable();
            $table->timestamps();
            $table->longText('remark')->nullable();
            $table->string('payslip', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fundreports');
    }
};
