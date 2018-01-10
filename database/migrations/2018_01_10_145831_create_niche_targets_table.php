<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNicheTargetsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('niche_targets', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('niche_id')->index('niche_id_idx');
			$table->string('target_username', 200)->index('target_username');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('niche_targets');
	}

}
