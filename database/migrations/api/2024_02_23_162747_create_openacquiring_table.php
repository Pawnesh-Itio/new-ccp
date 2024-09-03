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
        Schema::connection('pgsql_second')->create('openacquiring', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id');
            $table->string('mobile', 15);
            $table->string('email', 255);
            $table->string('merchant_id', 255)->nullable()->comment('Provide by openacquiring');
            $table->string('client_id', 255)->nullable()->comment('Provide by openacquiring');
            $table->string('client_secret', 255)->nullable()->comment('Provide by openacquiring');
            $table->boolean('status')->unsigned()->default(false)->comment('1 => Active, 0 => Inactive');
            $table->timestamp('created_at')->useCurrentOnUpdate()->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('pgsql_second')->dropIfExists('openacquiring');
    }
};
