<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDmLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
          Schema::create('dm_logs', function (Blueprint $table) {
            $table->integer('log_id')->increments();
            $table->integer('job_id')->nullable();
            $table->string('sender')->nullable();
            $table->string('recipient')->nullable();
            $table->mediumText('log_resp')->nullable();
            $table->mediumText('login_log_resp')->nullable();
            $table->mediumText('content')->nullable();
            $table->integer('error_handled')->default(0);
            $table->dateTime('date_logged')->getCurrent();
        });
    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
            Schema::dropIfExists('dm_logs');
   
    }
}
