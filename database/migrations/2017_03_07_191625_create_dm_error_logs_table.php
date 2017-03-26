<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDmErrorLogsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('dm_error_logs', function (Blueprint $table) {
            $table->integer('error_log_id')->increments();
            $table->integer('job_id')->nullable();
            $table->mediumText('error_log')->nullable();
            $table->string('sender_username')->nullable();
            $table->string('recipient_username')->nullable();
            $table->dateTime('date_logged')->getCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {

        Schema::dropIfExists('dm_error_logs');
    }
}