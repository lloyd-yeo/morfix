<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDmJobTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('dm_job', function(Blueprint $table)
		{
			$table->integer('job_id', true);
			$table->string('insta_username', 200)->nullable()->index('insta_username');
			$table->string('recipient_username', 200)->nullable();
			$table->string('recipient_insta_id')->nullable()->index('recipient_id_idx');
			$table->string('recipient_fullname', 300)->nullable();
			$table->dateTime('time_to_send')->nullable()->index('time_to_send_idx');
			$table->integer('fulfilled')->nullable()->default(0);
			$table->text('message', 65535)->nullable();
			$table->timestamp('date_job_inserted')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->integer('follow_up_order')->nullable()->default(0);
			$table->text('error_msg', 65535)->nullable();
			$table->text('success_msg', 65535)->nullable();
			$table->dateTime('updated_at')->nullable();
			$table->unique(['insta_username','recipient_insta_id','recipient_username','follow_up_order'], 'dm_job_unique_idx');
			$table->index(['insta_username','recipient_insta_id'], 'get_job_by_names_idx');
			$table->index(['insta_username','fulfilled'], 'fulfilled_idx');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('dm_job');
	}

}
