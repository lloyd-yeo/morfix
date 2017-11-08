<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserInstaPhotoPostScheduleTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_insta_photo_post_schedule', function(Blueprint $table)
		{
			$table->integer('schedule_id', true);
			$table->integer('insta_id')->nullable()->index('insta_id_idx');
			$table->string('insta_username', 200)->nullable()->index('insta_username_schedule_idx');
			$table->dateTime('date_to_post')->nullable();
			$table->string('image_path', 200)->nullable();
			$table->text('caption', 16777215)->nullable();
			$table->text('first_comment', 65535)->nullable();
			$table->integer('posted')->nullable()->default(0);
			$table->text('log', 16777215)->nullable();
			$table->text('failure_msg', 16777215)->nullable();
			$table->timestamp('actual_date_posted')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'))->index('date_idx');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_insta_photo_post_schedule');
	}

}
