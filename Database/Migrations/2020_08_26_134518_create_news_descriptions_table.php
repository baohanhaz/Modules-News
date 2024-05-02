<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsDescriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news_descriptions', function (Blueprint $table) {
          $table->string('id',128);
          $table->unsignedBigInteger('news_id'); 
          $table->integer('sort_order')->nullable();
          //FOREIGN KEY CONSTRAINTS
          $table->foreign('news_id')->references('id')->on('news')->onDelete('cascade');
          //SETTING THE PRIMARY KEYS
          $table->primary(['id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::dropIfExists('news_descriptions');
    }
}
