<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserInstaProfileLikeLogTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_insta_profile_like_log', function(Blueprint $table)
		{
			$table->integer('log_id', true);
			$table->string('insta_username')->nullable()->index('insta_username_idx');
			$table->string('target_username')->nullable()->index('target_username');
			$table->string('target_media')->nullable();
			$table->string('target_media_code')->nullable();
			$table->text('log', 65535)->nullable();
			$table->timestamp('date_liked')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'))->index('date_liked_idx');
			$table->unique(['insta_username','target_media'], 'user_like_media');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_insta_profile_like_log');
	}

}
