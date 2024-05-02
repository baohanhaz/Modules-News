<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsToBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news_to_banners', function (Blueprint $table) {
          $table->unsignedBigInteger('news_id');
          $table->unsignedBigInteger('banner_id');

          //FOREIGN KEY CONSTRAINTS
          $table->foreign('news_id')->references('id')->on('news')->onDelete('cascade');
          $table->foreign('banner_id')->references('id')->on('banners')->onDelete('cascade');

          //SETTING THE PRIMARY KEYS
          $table->primary(['news_id','banner_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news_to_banners');
    }
}
