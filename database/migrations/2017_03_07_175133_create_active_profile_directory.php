<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActiveProfileDirectory extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        Schema::create('active_profile_directory', function (Blueprint $table) {
            $table->string('insta_id')->primary();
            $table->string('insta_username')->nullable();
            $table->integer('follower_count')->nullable();
            $table->integer('posts_count')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('active_profile_directory');
    }

}
