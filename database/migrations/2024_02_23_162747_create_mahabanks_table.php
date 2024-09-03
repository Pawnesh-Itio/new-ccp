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
        Schema::create('mahabanks', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('bankid')->nullable();
            $table->string('bankcode', 50)->nullable();
            $table->string('bankname', 50)->nullable();
            $table->string('masterifsc', 50)->nullable();
            $table->string('url', 250)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mahabanks');
    }
};
