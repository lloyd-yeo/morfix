<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEngagementGroupJobTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('engagement_group_job', function(Blueprint $table)
		{
			$table->string('media_id', 200)->primary();
			$table->boolean('engaged')->nullable()->default(0);
			$table->timestamp('date_logged')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'))->index('date_logged');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('engagement_group_job');
	}

}
