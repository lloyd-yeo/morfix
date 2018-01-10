<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePaymentLogTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payment_log', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('email')->nullable()->index('email_idx');
			$table->string('plan')->nullable();
			$table->string('log')->nullable();
			$table->timestamps();
			$table->string('source')->nullable();
			$table->string('exception_type')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('payment_log');
	}

}
