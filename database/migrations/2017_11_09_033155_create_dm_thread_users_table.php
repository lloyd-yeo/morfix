<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDmThreadUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('dm_thread_users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('dm_thread_id')->unsigned()->nullable();
			$table->string('username', 300)->nullable();
			$table->integer('user_id')->unsigned()->nullable();
			$table->timestamps();
			$table->softDeletes();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('dm_thread_users');
	}

}
