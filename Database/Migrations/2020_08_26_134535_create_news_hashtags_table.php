<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsHashTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news_hashtags', function (Blueprint $table) {
          $table->id();
          $table->text('title')->nullable();
          $table->text('short_description')->nullable();
          $table->longText('description')->nullable();
          $table->text('meta_title')->nullable();
          $table->text('meta_description')->nullable();
          $table->text('meta_keyword')->nullable();
          $table->text('tag')->nullable();
          $table->text('slug');
          $table->text('image')->nullable();
          $table->integer('viewed')->nullable();
          $table->boolean('status')->default(1);
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
        Schema::dropIfExists('news_hashtags');
    }
}
