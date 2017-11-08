<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserInteractionFailedTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_interaction_failed', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('email', 125);
			$table->string('insta_username', 200);
			$table->integer('tier');
			$table->dateTime('timestamp')->nullable();
			$table->boolean('partition')->default(0);
			$table->text('failure_message', 65535)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_interaction_failed');
	}

}
