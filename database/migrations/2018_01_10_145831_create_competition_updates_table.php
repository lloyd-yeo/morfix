<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCompetitionUpdatesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('competition_updates', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('email')->nullable()->index('email_idx');
			$table->string('title', 45)->nullable();
			$table->text('content', 65535)->nullable();
			$table->string('type')->nullable();
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('competition_updates');
	}

}
