<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserStripeChargesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_stripe_charges', function(Blueprint $table)
		{
			$table->string('stripe_id', 100);
			$table->string('charge_id', 100);
			$table->dateTime('charge_created')->nullable();
			$table->string('invoice_id', 150)->nullable();
			$table->text('failure_msg', 65535)->nullable();
			$table->string('failure_code', 100)->nullable();
			$table->string('paying_card_id', 100)->nullable();
			$table->string('paying_card_brand', 100)->nullable();
			$table->integer('paying_card_lastfourdigit')->nullable();
			$table->boolean('paid')->nullable();
			$table->boolean('refunded')->nullable()->default(0);
			$table->boolean('eligible')->nullable()->default(0);
			$table->boolean('commission_given')->nullable()->default(0);
			$table->boolean('commission_calc')->nullable()->default(0);
			$table->boolean('testing_commission_given_july')->nullable()->default(0);
			$table->boolean('testing_commission_given')->nullable()->default(0);
			$table->primary(['stripe_id','charge_id']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_stripe_charges');
	}

}
