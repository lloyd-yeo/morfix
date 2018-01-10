<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserInstaTargetUsernameTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_insta_target_username', function(Blueprint $table)
		{
			$table->integer('target_id', true);
			$table->integer('insta_id')->nullable()->index('user_insta_target_username_idx');
			$table->string('insta_username')->nullable()->index('insta_username_idx');
			$table->string('target_username')->nullable()->index('target_username_idx');
			$table->boolean('invalid', 1)->nullable()->default('b\'0\'');
			$table->boolean('insufficient_followers', 1)->nullable()->default('b\'0\'');
			$table->dateTime('last_checked')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_insta_target_username');
	}

}
