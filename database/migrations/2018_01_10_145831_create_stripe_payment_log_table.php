<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStripePaymentLogTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('stripe_payment_log', function(Blueprint $table)
		{
			$table->integer('log_id', true);
			$table->string('email', 155)->nullable();
			$table->string('exception_type', 155)->nullable();
			$table->string('error_type')->nullable();
			$table->text('log', 65535)->nullable();
			$table->timestamp('date_logged')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('stripe_payment_log');
	}

}
