<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserPaypalAgreementsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_paypal_agreements', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('agreement_id', 125)->unique('agreement_id_UNIQUE');
			$table->string('email');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_paypal_agreements');
	}

}
