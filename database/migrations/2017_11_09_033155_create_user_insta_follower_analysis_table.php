<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserInstaFollowerAnalysisTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_insta_follower_analysis', function(Blueprint $table)
		{
			$table->integer('analysis_id', true);
			$table->string('insta_username', 200)->nullable()->index('email_idx');
			$table->timestamp('date')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->integer('follower_count')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_insta_follower_analysis');
	}

}
