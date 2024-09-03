<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('merchant_acquirer_mapping', function (Blueprint $table) {
            $table->increments('merchant_acquirer_mapping_id');
            $table->integer('merchant_id');
            $table->integer('acquirer_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchant_acquirer_mapping');
    }
};
