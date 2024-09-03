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
        Schema::connection('pgsql_second')->create('userdata', function (Blueprint $table) { 
            $table->integer('id', true);
            $table->integer('user_id')->unique();
            $table->string('name');
            $table->string('mobile', 10)->unique();
            $table->double('mainwallet', 11, 2)->default(0);
            $table->string('role_slug', 255);
            $table->integer('scheme_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('pgsql_second')->dropIfExists('userdata');
    }
};
