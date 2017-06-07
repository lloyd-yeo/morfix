<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMorfixInstagramProfileTargetHashtagsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        Schema::create('morfix_instagram_profile_target_hashtags', function (Blueprint $table) {
            $table->integer('id')->increments();
            $table->integer('insta_id')->nullable();
            $table->string('insta_username')->nullable();
            $table->string('hashtag')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {

        Schema::dropIfExists('morfix_instagram_profile_target_hashtags');
    }

}
