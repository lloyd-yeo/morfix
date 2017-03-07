<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStripePaymentLogsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        Schema::create('stripe_payment_logs', function (Blueprint $table) {
            $table->integer('log_id')->increments();
            $table->string('email')->nullable();
            $table->string('exception_type')->nullable();
            $table->string('error_type')->nullable();
            $table->text('log')->nullable();
            $table->dateTime('date_logged')->getCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('stripe_payment_logs');
    }

}
