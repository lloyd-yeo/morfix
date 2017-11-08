<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCreateInstaProfileLogTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('create_insta_profile_log', function(Blueprint $table)
		{
			$table->integer('log_id', true);
			$table->string('insta_username', 200)->nullable();
			$table->string('insta_pw', 200)->nullable();
			$table->string('email')->nullable();
			$table->string('password')->nullable();
			$table->text('error_msg', 16777215)->nullable();
			$table->timestamp('created')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('create_insta_profile_log');
	}

}
