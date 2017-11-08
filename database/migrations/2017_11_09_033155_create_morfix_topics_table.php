<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMorfixTopicsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('morfix_topics', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->text('topic', 65535)->nullable();
			$table->text('description', 65535)->nullable();
			$table->text('topic_url', 65535)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('morfix_topics');
	}

}
