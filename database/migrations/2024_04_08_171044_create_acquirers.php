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
        Schema::create('acquirers', function (Blueprint $table) {
            $table->increments('acquirer_id');
            $table->string('acquirer_name', 255);
            $table->string('api_endpoint', 255);
            $table->longText('fields');
            $table->enum('is_active', ['yes', 'no'])->default('yes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acquirers');
    }
};
