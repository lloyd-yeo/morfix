<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMorfixInstagramProfilePhotoPostSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('morfix_instagram_profile_photo_post_schedules', function (Blueprint $table) {
            $table->integer('schedule_id')->increments();
            $table->integer('insta_id')->nullable();
            $table->string('insta_username')->nullable();
            $table->dateTime('date_to_post')->nullable();
            $table->string('image_path')->nullable();
            $table->mediumText('caption')->nullable();
            $table->text('first_comment')->nullable();
            $table->integer('posted')->default(0);
            $table->mediumText('log')->nullable();
            $table->mediumText('failure_msg')->nullable();
            $table->dateTime('actual_date_posted')->getCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
        Schema::dropIfExists('morfix_instagram_profile_follower_analysis');
    
    }
}
