<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserInstaProfileCommentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_insta_profile_comment', function(Blueprint $table)
		{
			$table->integer('comment_id', true);
			$table->string('insta_username')->nullable()->index('insta_username_idx');
			$table->integer('ig_profile_id')->nullable();
			$table->text('comment', 65535)->nullable();
			$table->integer('general')->nullable()->default(1);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_insta_profile_comment');
	}

}
