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
        Schema::create('open_acquirer_capture_refunds', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('report_id')->index('report_id');
            $table->string('reference_id', 100);
            $table->double('amount', 10, 2);
            $table->tinyInteger('type')->nullable()->comment('1 => Capture, 2 => Refund');
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
        Schema::dropIfExists('open_acquirer_capture_refunds');
    }
};
