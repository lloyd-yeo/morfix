<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSlaveNodeConnectionDirectoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('slave_node_connection_directory', function(Blueprint $table)
		{
			$table->integer('id')->primary();
			$table->string('connection', 45)->nullable();
			$table->string('host', 45)->nullable();
			$table->string('port', 45)->nullable();
			$table->string('database', 45)->nullable();
			$table->string('username', 45)->nullable();
			$table->string('password', 45)->nullable();
			$table->string('charset', 45)->nullable()->default('utf8mb4');
			$table->string('collation', 45)->nullable()->default('utf8mb4_unicode_ci');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('slave_node_connection_directory');
	}

}
