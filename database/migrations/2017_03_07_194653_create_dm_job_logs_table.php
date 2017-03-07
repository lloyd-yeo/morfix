<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDmJobLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('dm_job_logs', function (Blueprint $table) {
            $table->integer('log_id')->increments();
            $table->integer('job_id')->nullable();
            $table->string('sender')->nullable();
            $table->string('recipient')->nullable();
            $table->mediumText('content')->nullable();
            $table->mediumText('log_resp')->nullable();
            $table->mediumText('login_log_resp')->nullable();
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
         Schema::dropIfExists('dm_job_logs');
    
    }
}
