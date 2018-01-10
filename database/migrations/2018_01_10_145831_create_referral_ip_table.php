<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateReferralIpTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('referral_ip', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('ip', 45)->unique('ip_UNIQUE');
			$table->integer('referrer');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('referral_ip');
	}

}
