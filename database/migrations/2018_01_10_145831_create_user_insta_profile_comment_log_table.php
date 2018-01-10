<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserInstaProfileCommentLogTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_insta_profile_comment_log', function(Blueprint $table)
		{
			$table->integer('log_id', true);
			$table->string('insta_username')->nullable();
			$table->string('target_username')->nullable();
			$table->string('target_insta_id')->nullable();
			$table->string('target_media')->nullable();
			$table->text('log', 65535)->nullable();
			$table->timestamp('date_commented')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->index(['insta_username','target_username'], 'user_target');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_insta_profile_comment_log');
	}

}
