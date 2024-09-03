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
        Schema::create('paytmlogs', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('txnid', 250)->nullable();
            $table->longText('response')->nullable();
            $table->longText('callbackresponse')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('paytmlogs');
    }
};
