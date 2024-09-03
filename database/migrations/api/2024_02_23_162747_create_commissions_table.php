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
        Schema::connection('pgsql_second')->create('commissions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('slab');
            $table->enum('type', ['flat', 'percent'])->default('flat');
            $table->double('apiuser', 11, 2)->default(0);
            $table->double('whitelable', 11, 2)->default(0);
            $table->double('md', 11, 2)->default(0);
            $table->double('distributor', 11, 2)->default(0);
            $table->double('retailer', 11, 2)->default(0);
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
        Schema::connection('pgsql_second')->dropIfExists('commissions');
    }
};
