<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEngagementGroupJobsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('engagement_group_job', function (Blueprint $table) {
            $table->string('media_id')->primary();
            $table->tinyInteger('engaged')->default(0);
            $table->dateTime('date_logged')->getCurrent();
            $table->dateTime('date_worked_on')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('engagement_group_job');
    }

}
