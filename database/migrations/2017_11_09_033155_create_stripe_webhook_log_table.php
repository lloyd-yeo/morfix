<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStripeWebhookLogTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('stripe_webhook_log', function(Blueprint $table)
		{
			$table->integer('stripe_log_id', true);
			$table->text('log', 16777215)->nullable();
			$table->timestamp('date_logged')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->text('error_log', 65535)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('stripe_webhook_log');
	}

}
