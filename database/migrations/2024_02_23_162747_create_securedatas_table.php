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
        Schema::create('securedatas', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id')->nullable()->unique();
            $table->string('apptoken', 500)->nullable();
            $table->string('ip', 50)->nullable();
            $table->integer('last_activity')->nullable();
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
        Schema::dropIfExists('securedatas');
    }
};
