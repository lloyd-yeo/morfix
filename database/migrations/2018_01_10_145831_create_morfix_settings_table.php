<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMorfixSettingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('morfix_settings', function(Blueprint $table)
		{
			$table->string('setting', 100);
			$table->integer('value');
			$table->primary(['setting','value']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('morfix_settings');
	}

}
