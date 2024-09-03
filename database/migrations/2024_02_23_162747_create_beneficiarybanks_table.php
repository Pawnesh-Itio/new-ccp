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
        Schema::create('beneficiarybanks', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('beneficiaryinfo_id')->nullable();
            $table->string('bankname', 100);
            $table->string('beneaccno', 50);
            $table->string('benemobile', 20);
            $table->string('benename', 100);
            $table->string('ifsc', 50);
            $table->boolean('verified')->default(false);
            $table->integer('mahabank_id')->nullable();
            $table->string('bankid', 100)->nullable();
            $table->integer('user_id');
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('beneficiarybanks');
    }
};
