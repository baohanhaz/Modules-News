<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsCategoriesTranslationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news_categories_translation', function (Blueprint $table) {
          $table->unsignedBigInteger('nc_id');
          $table->string('locale')->index();

          $table->text('name')->nullable();
          $table->text('description')->nullable();
          $table->text('meta_title')->nullable();
          $table->text('meta_description')->nullable();
          $table->text('meta_keyword')->nullable();

          $table->unique(['nc_id','locale']);
          $table->foreign('nc_id')->references('id')->on('news_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news_categories_translation');
    }
}
