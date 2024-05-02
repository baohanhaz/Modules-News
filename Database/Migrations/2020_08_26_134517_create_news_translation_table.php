<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsTranslationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news_translation', function (Blueprint $table) {
          $table->unsignedBigInteger('news_id');
          $table->string('locale')->index();

          $table->text('title')->nullable();
          $table->text('short_description')->nullable();
          $table->text('description')->nullable();
          $table->text('meta_title')->nullable();
          $table->text('meta_description')->nullable();
          $table->text('meta_keyword')->nullable();

          $table->unique(['news_id','locale']);
          $table->foreign('news_id')->references('id')->on('news')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::dropIfExists('news_translation');
    }
}
