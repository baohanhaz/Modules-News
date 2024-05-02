<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news_categories', function (Blueprint $table) {
            $table->id();
            $table->string('type',128)->nullable();
            $table->text('slug'); 
            $table->text('image')->nullable();
            $table->bigInteger('parent_id')->default(0)->nullable();  // Parent category.
            $table->integer('sort_order')->nullable();
            $table->boolean('header')->default(0)->nullable();
            $table->boolean('footer')->default(0)->nullable();
            $table->boolean('status')->default(1);
            $table->unsignedBigInteger('store_id')->default(0);
            $table->bigInteger('updater')->nullable(); // Update by user_id.
            $table->bigInteger('creator_id')->nullable(); // Create by user_id.
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
        Schema::dropIfExists('news_categories');
    }
}
