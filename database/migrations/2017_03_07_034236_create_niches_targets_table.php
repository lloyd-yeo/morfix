<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNichesTargetsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        Schema::create('niches_targets', function (Blueprint $table) {
            $table->integer('niche_id')->increments();
            $table->string('target')->nullable();
            $table->tinyInteger('target_type')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {

        Schema::dropIfExists('niches_targets');
    }

}
