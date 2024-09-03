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
        Schema::create('login_activitylogs', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('user_id', 100)->nullable()->index();
            $table->string('ip', 225)->nullable();
            $table->string('geo_location', 225)->nullable();
            $table->longText('user_agent')->nullable();
            $table->longText('parameters')->nullable();
            $table->string('url', 250)->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('login_activitylogs');
    }
};
