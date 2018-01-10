<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserStripeDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_stripe_details', function(Blueprint $table)
		{
			$table->string('stripe_id', 100)->primary();
			$table->string('email', 155)->nullable()->index('stripe_details_user_email_idx');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_stripe_details');
	}

}
