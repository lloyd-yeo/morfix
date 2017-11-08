<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateYourlsUrlTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('yourls_url', function(Blueprint $table)
		{
			$table->string('keyword', 200)->primary();
			$table->text('url', 65535)->index('url');
			$table->text('title', 16777215)->nullable();
			$table->timestamp('timestamp')->default(DB::raw('CURRENT_TIMESTAMP'))->index('timestamp');
			$table->string('ip', 41)->index('ip');
			$table->integer('clicks')->unsigned();
			$table->text('pixel', 65535)->nullable();
			$table->boolean('pixel_approved')->nullable()->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('yourls_url');
	}

}
