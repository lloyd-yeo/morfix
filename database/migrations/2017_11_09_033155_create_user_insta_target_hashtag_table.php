<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserInstaTargetHashtagTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_insta_target_hashtag', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('insta_id')->nullable()->index('insta_user_target_hashtag_idx');
			$table->string('insta_username')->nullable()->index('insta_username_idx');
			$table->string('hashtag')->nullable()->index('hashtag_idx');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_insta_target_hashtag');
	}

}
