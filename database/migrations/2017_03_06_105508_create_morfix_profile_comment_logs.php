<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMorfixProfileCommentLogs extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        Schema::create('morfix_profile_comment_logs', function (Blueprint $table) {
            $table->integer('log_id')->primary();
            $table->string('insta_username')->nullable();
            $table->string('target_username')->nullable();
            $table->string('target_media')->nullable();
            $table->text('log')->nullable();
            $table->dateTime('date_liked');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @ret urn void
     */
    public function down() {
        Schema::dropIfExists('morfix_profile_comment_logs');
    }

}
