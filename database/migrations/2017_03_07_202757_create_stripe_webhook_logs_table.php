<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStripeWebhookLogsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        Schema::create('stripe_webhook_logs', function (Blueprint $table) {
            $table->integer('stripe_log_id')->increments();
            $table->mediumText('log')->nullable();
            $table->dateTime('date_logged')->getCurrent();
            $table->text('error_log')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('stripe_webhook_logs');
    }

}
