<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToUserInstaProfileTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('user_insta_profile', function(Blueprint $table)
		{
			$table->foreign('user_id', 'insta_profile_user')->references('user_id')->on('user')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('niche', 'insta_profile_user_niche')->references('niche_id')->on('niches')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('user_insta_profile', function(Blueprint $table)
		{
			$table->dropForeign('insta_profile_user');
			$table->dropForeign('insta_profile_user_niche');
		});
	}

}
