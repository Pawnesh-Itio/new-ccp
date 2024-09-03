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
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type', 250)->nullable();
            $table->double('amount');
            $table->string('refno', 255);
            $table->string('paydate', 30)->nullable();
            $table->enum('status', ['success', 'pending', 'failed', 'approved', 'rejected'])->nullable()->default('pending')->index();
            $table->unsignedInteger('user_id')->index();
            $table->timestamps();
            $table->longText('remark')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
