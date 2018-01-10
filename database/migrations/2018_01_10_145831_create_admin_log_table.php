<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdminLogTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('admin_log', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('admin_email', 200)->nullable();
			$table->string('action', 45)->nullable();
			$table->text('message', 65535)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('admin_log');
	}

}
