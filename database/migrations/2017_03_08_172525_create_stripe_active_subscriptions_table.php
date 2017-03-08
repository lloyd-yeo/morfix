<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStripeActiveSubscriptionsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        Schema::create('stripe_active_subscriptions', function (Blueprint $table) {
            $table->string('stripe_id')->primary();
            $table->string('subscription_id');
            $table->dateTime('subscription_start_date')->nullable();
            $table->dateTime('subscription_end_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {

        Schema::dropIfExists('stripe_active_subscriptions');
    }

}
