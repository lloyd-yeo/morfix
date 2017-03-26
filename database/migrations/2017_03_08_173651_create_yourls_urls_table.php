<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYourlsUrlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('yourls_urls', function (Blueprint $table) {
            $table->string('keyword')->primary();
            $table->text('url');
            $table->mediumText('title')->nullable();
            $table->dateTime('timestamp')->getCurrent();
            $table->string('ip');
            $table->integer('clicks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
            Schema::dropIfExists('yourls_urls');
    
    }
}
