<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserAffiliatesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        Schema::create('user_affiliates', function (Blueprint $table) {
            $table->integer('affiliate_id')->increments();
            $table->integer('referrer')->nullable();
            $table->integer('referred')->nullable();
            $table->tinyInteger('refunded_premium')->default(0);
            $table->tinyInteger('refunded_pro')->default(0);
            $table->tinyInteger('refunded_business')->default(0);
            $table->tinyInteger('refunded_mastermind')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {

        Schema::dropIfExists('user_affiliates');
    }

}
