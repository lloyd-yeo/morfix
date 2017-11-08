<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserInstaProfileLikeLogArchiveTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_insta_profile_like_log_archive', function(Blueprint $table)
		{
			$table->integer('log_id', true);
			$table->string('insta_username')->nullable();
			$table->string('target_username')->nullable()->index('target_username_idx');
			$table->string('target_media')->nullable();
			$table->string('target_media_code')->nullable();
			$table->text('log', 65535)->nullable();
			$table->timestamp('date_liked')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'))->index('like_date_idx');
			$table->unique(['insta_username','target_media'], 'user_like_media');
			$table->index(['insta_username','target_username'], 'user_like_target_idx');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_insta_profile_like_log_archive');
	}

}
