<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMorfixInstagramProfileTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('morfix_instagram_profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->index(['id', 'user_id']);
            $table->string('email')->nullable();
            $table->string('insta_user_id')->nullable();
            $table->string('insta_username')->unique();
            $table->string('insta_pw')->nullable();
            $table->string('profile_pic_url')->nullable();
            $table->string('follower_count')->nullable();
            $table->string('profile_full_name')->nullable();
            $table->string('insta_new_follower_template')->nullable();
            $table->string('follow_up_message')->nullable();
            $table->string('num_posts')->nullable();
            $table->string('recent_activity_timestamp')->nullable();
            $table->tinyInteger('auto_dm_new_follower')->default(0);
            $table->tinyInteger('auto_dm_delay')->default(0);
            $table->tinyInteger('niche')->nullable()->default(0);
            $table->string('niche_target_counter')->default(0);
            $table->string('speed')->default("Normal");
            $table->dateTime('last_sent_dm')->nullable();
            $table->dateTime('temporary_ban')->nullable();
            $table->dateTime('next_follow_time')->nullable();
            $table->tinyInteger('like_quota')->default(20);
            $table->tinyInteger('comment_quota')->default(6);
            $table->tinyInteger('unfollow')->default(0);
            $table->tinyInteger('auto_interaction_ban')->default(0);
            $table->dateTime('auto_interaction_ban_time')->nullable();
            $table->string('login_log')->nullable();
            $table->dateTime('last_instagram_login')->nullable();
            $table->integer('follow_cycle')->default(1000);
            $table->integer('daily_follow_quota')->default(170);
            $table->integer('daily_unfollow_quota')->default(170);
            $table->tinyInteger('auto_interaction')->default(0);
            $table->tinyInteger('auto_comment')->default(0);
            $table->tinyInteger('auto_like')->default(0);
            $table->tinyInteger('auto_follow')->default(0);
            $table->tinyInteger('auto_follow_ban')->default(0);
            $table->dateTime('auto_follow_ban_time')->nullable();
            $table->tinyInteger('auto_unfollow')->default(0);
            $table->tinyInteger('auto_unfollow_ban')->default(0);
            $table->dateTime('auto_unfollow_ban_time')->nullable();
            $table->tinyInteger('unfollow_unfollowed')->default(0);
            $table->integer('follow_min_followers')->default(0);
            $table->integer('follow_max_followers')->default(0);
            $table->integer('follow_unfollow_delay')->default(300);
            $table->tinyInteger('invalid_user')->default(0);
            $table->tinyInteger('checkpoint_required')->default(0);
            $table->string('proxy')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('morfix_instagram_profiles');
    }

}
