<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMorfixPlansTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('morfix_plans', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('name', 45);
			$table->string('paypal_id', 45);
			$table->string('stripe_id', 45);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('morfix_plans');
	}

}
