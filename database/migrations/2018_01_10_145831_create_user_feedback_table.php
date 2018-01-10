<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserFeedbackTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_feedback', function(Blueprint $table)
		{
			$table->integer('feedback_id', true);
			$table->text('feedback', 65535)->nullable();
			$table->integer('user_id')->nullable();
			$table->integer('status')->nullable()->default(0);
			$table->timestamp('date_posted')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_feedback');
	}

}
