<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDmThreadItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('dm_thread_items', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('dm_thread_id')->unsigned()->nullable();
			$table->string('item_id', 300)->nullable();
			$table->string('item_type', 100)->nullable();
			$table->text('item_text')->nullable();
			$table->string('user_id', 100)->nullable();
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
		Schema::drop('dm_thread_items');
	}

}
