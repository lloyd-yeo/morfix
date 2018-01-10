<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserInstaProfileMediaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_insta_profile_media', function(Blueprint $table)
		{
			$table->string('insta_username', 200);
			$table->string('media_id', 200);
			$table->string('code', 200)->nullable();
			$table->string('image_url', 300)->nullable();
			$table->timestamp('created_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->primary(['insta_username','media_id']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_insta_profile_media');
	}

}
