<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlacklistedUsernameTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {


        Schema::create('blacklisted_username', function (Blueprint $table) {
            $table->string('username')->primary();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('blacklisted_username');
    }

}
