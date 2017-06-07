<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMorfixInstagramProfileFollowerAnalysisTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        Schema::create('morfix_instagram_profile_follower_analysis', function (Blueprint $table) {
            $table->integer('analysis_id')->increments();
            $table->string('insta_username')->nullable();
            $table->dateTime('date')->getCurrent();
            $table->integer('follower_count')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {

        Schema::dropIfExists('morfix_instagram_profile_follower_analysis');
    }

}
