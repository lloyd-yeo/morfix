<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMorfixProfileFollowLogs extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        Schema::create('morfix_profile_follow_logs', function (Blueprint $table) {
            $table->integer('log_id')->increments();
            $table->string('insta_username')->nullable();
            $table->string('follower_username')->nullable();
            $table->string('follower_id')->nullable();
            $table->text('log')->nullable();
            $table->dateTime('date_inserted')->useCurrent();
            $table->integer('added_delay')->nullable();
            $table->tinyInteger('follow')->default(1);
            $table->string('follow_success')->nullable();
            $table->tinyInteger('unfollowed')->default(0);
            $table->string('unfollowed_success')->nullable();
            $table->text('unfollow_log')->nullable();
            $table->dateTime('date_unfollowed')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('morfix_profile_follow_logs');
    }

}
