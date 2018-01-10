<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBraintreeTransactionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('braintree_transactions', function(Blueprint $table)
		{
			$table->string('id', 100)->primary();
			$table->string('status', 200)->nullable();
			$table->string('type', 100)->nullable();
			$table->decimal('amount', 13, 4)->nullable();
			$table->timestamps();
			$table->string('bt_cc_token', 45)->nullable();
			$table->string('plan_id', 45)->nullable();
			$table->string('sub_id', 45)->nullable();
			$table->string('braintree_id', 45)->nullable();
			$table->string('user_email')->nullable();
			$table->boolean('comms_given')->nullable()->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('braintree_transactions');
	}

}
