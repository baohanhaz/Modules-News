<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsToCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news_to_categories', function (Blueprint $table) {
          $table->unsignedBigInteger('news_id');
          $table->unsignedBigInteger('nc_id');

          //FOREIGN KEY CONSTRAINTS
          $table->foreign('news_id')->references('id')->on('news')->onDelete('cascade');
          $table->foreign('nc_id')->references('id')->on('news_categories')->onDelete('cascade');

          //SETTING THE PRIMARY KEYS
          $table->primary(['news_id','nc_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news_to_categories');
    }
}
