<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStripeInvoiceTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('stripe_invoice', function (Blueprint $table) {
            $table->string('stripe_id');
            $table->string('charge_id');
            $table->dateTime('charge_created')->nullable();
            $table->string('invoice_id')->primary();
            $table->text('failure_msg')->nullable();
            $table->string('failure_code')->nullable();
            $table->string('paying_card_id')->nullable();
            $table->string('paying_card_brand')->nullable();
            $table->Integer('paying_card_lastfourdigit')->nullable();
            $table->tinyInteger('charge_paid')->nullable();
            $table->tinyInteger('refunded')->default(0);
            $table->tinyInteger('commission_given')->default(0);
            $table->tinyInteger('commission_calculated')->default(0);
            $table->dateTime('invoice_date')->nullable();
            $table->Integer('subscription_id')->nullable();
            $table->dateTime('invoice_start_date')->nullable();
            $table->dateTime('invoice_expiry_date')->nullable();
            $table->tinyInteger('invoice_paid')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('stripe_invoice');
    }

}
