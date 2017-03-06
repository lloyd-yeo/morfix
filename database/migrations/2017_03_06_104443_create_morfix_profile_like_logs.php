<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMorfixProfileLikeLogs extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('morfix_profile_like_logs', function (Blueprint $table) {
            $table->integer('log_id')->primary();
            $table->string('insta_username')->nullable();
            $table->string('target_username')->nullable();
            $table->string('target_media')->nullable();
            $table->string('target_media_code')->nullable();
            $table->text('log')->nullable();
            $table->dateTime('date_liked');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('morfix_profile_like_logs');
    }

}
