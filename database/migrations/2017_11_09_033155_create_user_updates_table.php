<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserUpdatesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_updates', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('email')->nullable()->index('email_idx');
			$table->string('title', 45)->nullable();
			$table->string('content')->nullable();
			$table->string('type')->nullable();
			$table->timestamp('created_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'))->index('date_idx');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_updates');
	}

}
