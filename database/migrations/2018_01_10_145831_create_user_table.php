<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user', function(Blueprint $table)
		{
			$table->integer('user_id', true);
			$table->string('email')->nullable()->index('email_idx');
			$table->string('password')->nullable();
			$table->integer('num_acct')->nullable();
			$table->dateTime('last_login')->nullable();
			$table->integer('active')->nullable()->default(1);
			$table->string('verification_token')->nullable();
			$table->string('timezone')->nullable();
			$table->string('stripe_id')->nullable()->index('stripe_id_idx');
			$table->integer('user_tier')->nullable()->default(0);
			$table->integer('premium_pro')->nullable()->default(0);
			$table->integer('biz_pro')->nullable()->default(0);
			$table->string('name')->nullable();
			$table->integer('trial_activation')->nullable()->default(0);
			$table->dateTime('trial_end_date')->nullable();
			$table->integer('close_dm_tut')->nullable()->default(0);
			$table->integer('close_dashboard_tut')->nullable()->default(0);
			$table->integer('close_interaction_tut')->nullable()->default(0);
			$table->integer('close_profile_tut')->nullable()->default(0);
			$table->integer('close_scheduling_tut')->nullable()->default(0);
			$table->string('ref_keyword', 45)->nullable();
			$table->string('paypal_email', 225)->nullable();
			$table->decimal('all_time_commission', 13, 4)->nullable()->default(0.0000);
			$table->decimal('pending_commission', 13, 4)->nullable()->default(0.0000);
			$table->integer('tier')->nullable()->default(1);
			$table->boolean('admin')->nullable()->default(0);
			$table->boolean('vip')->nullable()->default(0);
			$table->timestamps();
			$table->boolean('engagement_quota')->nullable()->default(1);
			$table->string('remember_token', 100)->nullable();
			$table->decimal('pending_commission_payable', 13, 4)->nullable()->default(0.0000);
			$table->boolean('paypal')->nullable()->default(0);
			$table->dateTime('last_pay_out_date')->nullable();
			$table->integer('partition')->nullable()->default(0);
			$table->decimal('testing_pending_commission', 13, 4)->nullable()->default(0.0000);
			$table->decimal('testing_pending_commission_payable', 13, 4)->nullable()->default(0.0000);
			$table->dateTime('testing_last_pay_out_date')->nullable();
			$table->decimal('paid_amount', 13, 4)->nullable();
			$table->decimal('testing_all_time_commission', 13, 4)->nullable()->default(0.0000);
			$table->boolean('reminder_igprofile')->nullable()->default(0);
			$table->string('braintree_id', 50)->nullable()->index('braintree_id_idx');
			$table->boolean('trial_upgrade')->nullable()->default(0);
			$table->boolean('eligibile_tshirt')->nullable()->default(0);
			$table->boolean('is_competitor')->nullable()->default(0);
			$table->string('last_used_proxy', 100)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user');
	}

}
