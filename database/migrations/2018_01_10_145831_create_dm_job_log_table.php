<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDmJobLogTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('dm_job_log', function(Blueprint $table)
		{
			$table->integer('log_id', true);
			$table->integer('job_id')->nullable();
			$table->string('sender', 200)->nullable();
			$table->string('recipient', 200)->nullable();
			$table->text('content', 16777215)->nullable();
			$table->text('log_resp', 16777215)->nullable();
			$table->text('login_log_resp', 16777215)->nullable();
			$table->timestamp('date_logged')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('dm_job_log');
	}

}
