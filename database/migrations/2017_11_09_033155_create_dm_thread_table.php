<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDmThreadTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('dm_thread', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('thread_id', 300);
			$table->string('named', 300);
			$table->binary('is_spam', 1)->nullable();
			$table->binary('muted', 1)->nullable();
			$table->string('thread_type', 300)->nullable();
			$table->string('thread_title', 300)->nullable();
			$table->binary('is_pin', 1)->nullable();
			$table->timestamps();
			$table->softDeletes();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('dm_thread');
	}

}
