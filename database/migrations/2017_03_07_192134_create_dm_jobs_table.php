<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDmJobsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('dm_jobs', function (Blueprint $table) {
            $table->integer('job_id')->increments();
            $table->string('insta_username')->nullable();
            $table->string('recipient_username')->nullable();
            $table->string('recipient_insta_id')->nullable();
            $table->string('recipient_fullname')->nullable();
            $table->dateTime('time_to_send')->nullable();
            $table->integer('fulfilled')->default(0);
            $table->text('message')->nullable();
            $table->dateTime('date_job_inserted')->getCurrent();
            $table->string('source_insertion')->nullable();
            $table->integer('num_attempts')->default(0);
            $table->integer('follow_up_order')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('dm_jobs');
    }

}
