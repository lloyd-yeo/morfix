<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYourlsLogsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        Schema::create('yourls_logs', function (Blueprint $table) {
            $table->integer('click_id')->increments();
            $table->dateTime('click_time');
            $table->string('shorturl');
            $table->string('referrer');
            $table->string('user_agent');
            $table->string('ip_address');
            $table->string('country_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {

        Schema::dropIfExists('yourls_logs');
    }

}
