<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserStripeActiveSubscriptionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_stripe_active_subscription', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('stripe_id', 100)->index('stripe_id_idx');
			$table->string('subscription_id', 100)->index('subscription_id_idx');
			$table->string('status', 45);
			$table->dateTime('start_date');
			$table->dateTime('end_date');
			$table->string('stripe_subscription_id', 155)->index('stripe_sub_id_idx');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_stripe_active_subscription');
	}

}
