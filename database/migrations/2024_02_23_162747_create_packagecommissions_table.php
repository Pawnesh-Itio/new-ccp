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
        Schema::create('packagecommissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slab');
            $table->enum('type', ['flat', 'percent'])->default('flat');
            $table->double('value', 11, 2)->default(0);
            $table->string('product', 250)->nullable();
            $table->integer('scheme_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('packagecommissions');
    }
};
