<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMorfixQnaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('morfix_qna', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->text('question', 65535);
			$table->text('answer', 65535);
			$table->integer('topic_id')->default(1);
			$table->timestamp('written_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->dateTime('updated_at')->nullable();
			$table->string('author', 45)->nullable()->default('Natalie');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('morfix_qna');
	}

}
