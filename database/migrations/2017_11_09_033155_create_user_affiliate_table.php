<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserAffiliateTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_affiliate', function(Blueprint $table)
		{
			$table->integer('affiliate_id', true);
			$table->integer('referrer')->nullable();
			$table->integer('referred')->nullable()->unique('referred_UNIQUE');
			$table->boolean('refunded_premium')->nullable()->default(0);
			$table->boolean('refunded_pro')->nullable()->default(0);
			$table->boolean('refunded_business')->nullable()->default(0);
			$table->boolean('refunded_mastermind')->nullable()->default(0);
			$table->integer('active')->nullable()->default(1);
			$table->string('referrer_email', 191)->nullable();
			$table->string('referred_email', 191)->nullable();
			$table->unique(['referrer','referred'], 'unique_user_referral');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_affiliate');
	}

}
