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
        Schema::create('companies', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('companyname');
            $table->string('shortname', 15)->nullable();
            $table->string('website');
            $table->longText('logo')->nullable();
            $table->enum('status', ['0', '1'])->default('1');
            $table->timestamps();
            $table->string('senderid', 250)->nullable();
            $table->string('smsuser', 250)->nullable();
            $table->string('smspwd', 250)->nullable();
            $table->string('uticode', 250)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
    }
};
