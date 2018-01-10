<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEngagementJobQueueTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('engagement_job_queue', function(Blueprint $table)
		{
			$table->integer('job_id', true);
			$table->string('media_id');
			$table->string('insta_username')->index('insta_username_idx');
			$table->integer('action')->default(0);
			$table->integer('fulfilled')->default(0);
			$table->timestamp('date_to_work_on')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->unique(['media_id','action','insta_username'], 'unique_e_job_idx');
			$table->index(['insta_username','action','fulfilled'], 'unfulfilled_idx');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('engagement_job_queue');
	}

}
