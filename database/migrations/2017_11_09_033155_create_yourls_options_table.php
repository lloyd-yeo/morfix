<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateYourlsOptionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('yourls_options', function(Blueprint $table)
		{
			$table->bigInteger('option_id', true)->unsigned();
			$table->string('option_name', 64)->default('')->index('option_name');
			$table->text('option_value');
			$table->primary(['option_id','option_name']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('yourls_options');
	}

}
