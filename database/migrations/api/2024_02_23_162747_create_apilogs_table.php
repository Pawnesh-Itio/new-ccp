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
        Schema::connection('pgsql_second')->create('apilogs', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('url')->nullable();
            $table->string('modal')->nullable();
            $table->string('txnid')->nullable();
            $table->longText('header')->nullable();
            $table->longText('request')->nullable();
            $table->longText('response')->nullable();
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
        Schema::connection('pgsql_second')->dropIfExists('apilogs');
    }
};
