<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToUserStripeActiveSubscriptionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('user_stripe_active_subscription', function(Blueprint $table)
		{
			$table->foreign('stripe_id', 'stripe_id')->references('stripe_id')->on('user_stripe_details')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('user_stripe_active_subscription', function(Blueprint $table)
		{
			$table->dropForeign('stripe_id');
		});
	}

}
