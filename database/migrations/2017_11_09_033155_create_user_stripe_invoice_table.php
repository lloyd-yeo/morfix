<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserStripeInvoiceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_stripe_invoice', function(Blueprint $table)
		{
			$table->string('stripe_id', 50);
			$table->string('invoice_id', 50);
			$table->string('charge_id', 30)->nullable();
			$table->dateTime('invoice_date')->nullable();
			$table->string('subscription_id', 10);
			$table->boolean('paid')->nullable();
			$table->dateTime('start_date')->nullable();
			$table->dateTime('expiry_date')->nullable();
			$table->primary(['stripe_id','invoice_id','subscription_id']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_stripe_invoice');
	}

}
