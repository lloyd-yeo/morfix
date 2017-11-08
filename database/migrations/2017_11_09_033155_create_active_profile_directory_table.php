<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateActiveProfileDirectoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('active_profile_directory', function(Blueprint $table)
		{
			$table->string('insta_username', 155)->nullable();
			$table->string('insta_id', 100)->index('insta_id_idx');
			$table->integer('follower_count')->nullable();
			$table->integer('posts_count')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('active_profile_directory');
	}

}
