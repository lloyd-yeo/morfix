<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateYourlsLogTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('yourls_log', function(Blueprint $table)
		{
			$table->integer('click_id', true);
			$table->dateTime('click_time');
			$table->string('shorturl', 200)->index('shorturl');
			$table->string('referrer', 200);
			$table->string('user_agent');
			$table->string('ip_address', 41);
			$table->char('country_code', 2);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('yourls_log');
	}

}
