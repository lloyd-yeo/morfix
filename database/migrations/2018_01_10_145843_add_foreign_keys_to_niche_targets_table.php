<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToNicheTargetsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('niche_targets', function(Blueprint $table)
		{
			$table->foreign('niche_id', 'niche_id')->references('niche_id')->on('niches')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('niche_targets', function(Blueprint $table)
		{
			$table->dropForeign('niche_id');
		});
	}

}
