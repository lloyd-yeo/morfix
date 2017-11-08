<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePaypalChargesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('paypal_charges', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('email')->nullable();
			$table->string('agreement_id', 100)->nullable();
			$table->string('transaction_id', 100)->nullable();
			$table->decimal('amount', 13, 4)->nullable();
			$table->string('subscription_id', 10)->nullable();
			$table->text('status', 65535)->nullable();
			$table->text('transaction_type', 65535)->nullable();
			$table->string('referrer_email')->nullable();
			$table->string('payer_email', 155)->nullable();
			$table->text('payer_name', 65535)->nullable();
			$table->dateTime('time_stamp')->nullable();
			$table->boolean('commission_given')->nullable()->default(0);
			$table->boolean('commission_calc')->nullable()->default(0);
			$table->boolean('testing_commission_given_july')->nullable()->default(0);
			$table->boolean('testing_commission_given')->nullable()->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('paypal_charges');
	}

}
