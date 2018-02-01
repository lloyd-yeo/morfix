<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserCancellationFeedbacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_cancellation_feedbacks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email')->nullable();
            $table->text('first_answer');
            $table->text('second_answer');
            $table->text('third_answer');
            $table->text('fourth_answer');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_cancellation_feedbacks');
    }
}
