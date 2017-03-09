<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('timezone')->nullable();
            $table->string('verification_token')->unique();
            $table->timestamp('last_login')->nullable();
            $table->integer('number_of_accts')->default(1);
            
            //engagement group
            $table->tinyInteger('engagement_quota')->default(0);
            
            //laravel defaults
            $table->rememberToken();
            $table->timestamps();
            
            //inactive/active
            $table->tinyInteger('active')->default(1);
            
            //tiering
            $table->tinyInteger('admin')->default(0);
            $table->tinyInteger('vip')->default(0);
            $table->tinyInteger('tier')->default(1);
            
            //trial
            $table->tinyInteger('trial_activation')->default(0);
            $table->timestamp('trial_end_date')->nullable();
            
            //tutorials
            $table->tinyInteger('close_dm_tut')->default(0);
            $table->tinyInteger('close_dashboard_tut')->default(0);
            $table->tinyInteger('close_interaction_tut')->default(0);
            $table->tinyInteger('close_profile_tut')->default(0);
            $table->tinyInteger('close_scheduling_tut')->default(0);
            
            //commission
            $table->string('paypal_email')->nullable();
            $table->decimal('pending_commission', 13, 4)->default(0);
            $table->decimal('total_commission', 13, 4)->default(0); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
