<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsCategoryPathTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news_category_path', function (Blueprint $table) {
          $table->unsignedBigInteger('category_id');
          $table->unsignedBigInteger('parent_id');  // Parent category.
          $table->integer('level')->default(0);

          //FOREIGN KEY CONSTRAINTS
          $table->foreign('category_id')->references('id')->on('news_categories')->onDelete('cascade');
          $table->foreign('parent_id')->references('id')->on('news_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news_category_path');
    }
}
