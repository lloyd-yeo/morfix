<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserFeedbackTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        Schema::create('user_feedback', function (Blueprint $table) {
            $table->integer('feedback_id')->increments();
            $table->text('feedback')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('status')->default(0);
            $table->dateTime('date_posted')->getCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {

        Schema::dropIfExists('user_feedback');
    }

}
