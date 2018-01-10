<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserInstaProfileFollowLogTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_insta_profile_follow_log', function(Blueprint $table)
		{
			$table->integer('log_id', true);
			$table->string('insta_username', 200)->nullable()->index('insta_un_ft_idx');
			$table->string('follower_username', 200)->nullable()->index('follower_un_ft_idx');
			$table->string('follower_id', 200)->nullable()->index('follower_id_idx');
			$table->text('log', 65535)->nullable();
			$table->timestamp('date_inserted')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->integer('added_delay')->nullable();
			$table->integer('follow')->nullable()->default(1);
			$table->string('follow_success', 45)->nullable();
			$table->integer('unfollowed')->nullable()->default(0);
			$table->text('unfollow_log', 65535)->nullable();
			$table->dateTime('date_unfollowed')->nullable();
			$table->unique(['insta_username','follower_username','follower_id'], 'unique_idx');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_insta_profile_follow_log');
	}

}
