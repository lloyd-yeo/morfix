<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMorfixInstagramProfilePostsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        Schema::create('morfix_instagram_profile_posts', function (Blueprint $table) {
            $table->string('insta_username')->primary();
            $table->string('media_id');
            $table->string('image_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {

        Schema::dropIfExists('morfix_instagram_profile_posts');
    }

}
