<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserInstaProfileTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_insta_profile', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('user_id')->nullable()->index('insta_profile_id_idx');
			$table->string('email')->nullable()->index('insta_profile_email');
			$table->string('insta_user_id')->nullable();
			$table->string('insta_username', 200)->index('insta_username_idx');
			$table->string('insta_pw', 200)->nullable();
			$table->string('profile_pic_url', 200)->nullable();
			$table->integer('follower_count')->nullable();
			$table->string('profile_full_name', 200)->nullable();
			$table->text('insta_new_follower_template', 16777215)->nullable();
			$table->text('follow_up_message', 16777215)->nullable();
			$table->integer('num_posts')->nullable();
			$table->decimal('recent_activity_timestamp', 15, 4)->nullable()->default(0.0000);
			$table->integer('auto_dm_new_follower')->nullable()->default(0);
			$table->boolean('auto_dm_delay', 1)->nullable()->default('b\'0\'');
			$table->dateTime('last_sent_dm')->nullable();
			$table->dateTime('temporary_ban')->nullable();
			$table->integer('dm_probation')->nullable()->default(0);
			$table->integer('niche')->nullable()->default(0)->index('insta_profile_user_niche_idx');
			$table->string('speed', 200)->nullable()->default('Slow');
			$table->dateTime('next_follow_time')->nullable();
			$table->integer('unfollow')->nullable()->default(0);
			$table->text('login_log', 65535)->nullable();
			$table->dateTime('last_instagram_login')->nullable();
			$table->integer('follow_cycle')->nullable()->default(300);
			$table->integer('follow_quota')->nullable()->default(18);
			$table->integer('unfollow_quota')->nullable()->default(18);
			$table->integer('like_quota')->nullable()->default(20);
			$table->integer('comment_quota')->nullable()->default(6);
			$table->integer('auto_interaction')->nullable()->default(0);
			$table->integer('gender_filter')->nullable()->default(0);
			$table->integer('auto_comment')->nullable()->default(0);
			$table->integer('auto_like')->nullable()->default(0);
			$table->integer('auto_follow')->nullable()->default(0);
			$table->integer('auto_follow_ban')->nullable()->default(0);
			$table->dateTime('auto_follow_ban_time')->nullable();
			$table->integer('auto_unfollow')->nullable()->default(0);
			$table->integer('auto_unfollow_ban')->nullable()->default(0);
			$table->dateTime('auto_unfollow_ban_time')->nullable();
			$table->integer('follow_max_followers')->nullable()->default(0);
			$table->dateTime('next_like_time')->nullable();
			$table->integer('auto_like_ban')->nullable()->default(0);
			$table->dateTime('auto_like_ban_time')->nullable();
			$table->integer('auto_comment_ban')->nullable()->default(0);
			$table->dateTime('auto_comment_ban_time')->nullable();
			$table->dateTime('next_comment_time')->nullable();
			$table->integer('unfollow_unfollowed')->nullable()->default(0);
			$table->integer('follow_min_followers')->nullable()->default(0);
			$table->integer('follow_unfollow_delay')->nullable()->default(300);
			$table->integer('follow_recent_engaged')->nullable()->default(0);
			$table->integer('checkpoint_required')->nullable()->default(0);
			$table->integer('account_disabled')->nullable()->default(0);
			$table->integer('invalid_user')->nullable()->default(0);
			$table->integer('incorrect_pw')->nullable()->default(0);
			$table->integer('invalid_proxy')->nullable()->default(0);
			$table->integer('feedback_required')->nullable()->default(0);
			$table->integer('comment_feedback_required')->nullable()->default(0);
			$table->text('error_msg', 65535)->nullable();
			$table->string('proxy', 100)->nullable();
			$table->timestamps();
			$table->integer('daily_likes')->nullable()->default(0);
			$table->integer('daily_comments')->nullable()->default(0);
			$table->integer('daily_follows')->nullable()->default(0);
			$table->integer('daily_unfollows')->nullable()->default(0);
			$table->integer('total_likes')->nullable()->default(0);
			$table->integer('total_comments')->nullable()->default(0);
			$table->integer('total_follows')->nullable()->default(0);
			$table->integer('total_unfollows')->nullable()->default(0);
			$table->integer('auto_interactions_working')->nullable()->default(0);
			$table->integer('auto_like_working')->nullable()->default(0);
			$table->integer('auto_follow_working')->nullable()->default(0);
			$table->integer('auto_comment_working')->nullable()->default(0);
			$table->primary(['id','insta_username']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_insta_profile');
	}

}
