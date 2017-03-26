<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMorfixInstagramProfileTargetUsernamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('morfix_instagram_profile_target_usernames', function (Blueprint $table) {
            $table->integer('target_id')->increments();
            $table->integer('insta_id')->nullable();
            $table->string('insta_username')->nullable();
            $table->string('target_username')->nullable();
            $table->tinyInteger('invalid')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
        Schema::dropIfExists('morfix_instagram_profile_target_usernames');
    
    }
}
