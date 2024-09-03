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
        Schema::create('providers', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name');
            $table->string('recharge1')->nullable();
            $table->string('recharge2')->nullable();
            $table->string('logo', 500)->nullable();
            $table->integer('api_id')->nullable();
            $table->enum('type', ['mobile', 'dth', 'electricity', 'pancard', 'dmt', 'aeps', 'fund', 'nsdlpan', 'tax', 'lpggas', 'gasutility', 'landline', 'postpaid', 'broadband', 'water', 'loanrepay', 'lifeinsurance', 'fasttag', 'cable', 'insurance', 'schoolfees', 'muncipal', 'housing', 'idstock', 'aadharpay', 'upi', 'payout'])->default('mobile');
            $table->enum('status', ['0', '1'])->default('1');
            $table->string('paramcount', 250)->nullable();
            $table->string('manditcount', 250)->nullable();
            $table->string('paramname', 250)->nullable();
            $table->string('maxlength', 250)->nullable();
            $table->string('minlength', 250)->nullable();
            $table->string('regex', 250)->nullable();
            $table->string('ismandatory', 100)->nullable();
            $table->string('fieldtype', 100)->nullable();
            $table->integer('state')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('providers');
    }
};
