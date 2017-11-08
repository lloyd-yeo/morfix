<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDmErrorLogTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('dm_error_log', function(Blueprint $table)
		{
			$table->integer('error_log_id', true);
			$table->integer('job_id')->nullable();
			$table->text('error_log', 16777215)->nullable();
			$table->string('sender_username', 45)->nullable();
			$table->string('recipient_username', 45)->nullable();
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
		Schema::drop('dm_error_log');
	}

}
