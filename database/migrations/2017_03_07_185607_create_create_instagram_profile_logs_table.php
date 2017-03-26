<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreateInstagramProfileLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('create_instagram_profile_logs', function (Blueprint $table) {
            $table->integer('log_id')->increments();
            $table->string('insta_username')->nullable();
            $table->string('insta_pw')->nullable();
            $table->string('email')->nullable();
            $table->mediumText('error_msg')->nullable();
            $table->dateTime('created')->getCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('create_instagram_profile_logs');
    
    }
}
