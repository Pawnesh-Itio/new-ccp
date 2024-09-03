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
        Schema::create('mahaagents', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('bc_id', 50)->nullable();
            $table->string('bbps_agent_id', 250)->nullable();
            $table->string('bbps_id', 250)->nullable();
            $table->string('bc_f_name');
            $table->string('bc_m_name')->nullable();
            $table->string('bc_l_name')->nullable();
            $table->string('emailid')->nullable();
            $table->string('phone1')->nullable();
            $table->string('phone2')->nullable();
            $table->string('bc_dob')->nullable();
            $table->string('bc_state')->nullable();
            $table->string('bc_district')->nullable();
            $table->string('bc_address')->nullable();
            $table->string('bc_block')->nullable();
            $table->string('bc_city')->nullable();
            $table->string('bc_landmark')->nullable();
            $table->string('bc_loc')->nullable();
            $table->string('bc_mohhalla')->nullable();
            $table->string('bc_pan')->nullable();
            $table->string('bc_pincode')->nullable();
            $table->string('shopname')->nullable();
            $table->longText('shopType')->nullable();
            $table->longText('qualification')->nullable();
            $table->longText('population')->nullable();
            $table->longText('locationType')->nullable();
            $table->enum('status', ['pending', 'success', 'rejected'])->default('pending');
            $table->integer('user_id')->nullable();
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
        Schema::dropIfExists('mahaagents');
    }
};
