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
        Schema::create('acquirerfields', function (Blueprint $table) {
            $table->increments('field_id');
            $table->integer('acquirer_id');
            $table->string('field_name', 255);
            $table->string('field_label', 255);
            $table->string('field_type', 50);
            $table->enum('is_active', ['yes', 'no'])->default('yes');
            $table->timestamps();
        }); 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acquirerfields');
    }
};
