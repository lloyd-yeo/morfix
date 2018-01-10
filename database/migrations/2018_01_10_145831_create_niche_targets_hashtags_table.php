<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNicheTargetsHashtagsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('niche_targets_hashtags', function(Blueprint $table)
		{
			$table->integer('niche_hashtag_id', true);
			$table->integer('niche_id')->nullable();
			$table->string('hashtag', 1000)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('niche_targets_hashtags');
	}

}
