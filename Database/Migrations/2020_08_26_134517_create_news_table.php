<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('type',128)->nullable();
            $table->text('tag')->nullable();
            $table->text('slug')->nullable(); 
            $table->text('image')->nullable();
            $table->integer('viewed')->nullable();
            $table->boolean('status')->default(1);
            $table->unsignedBigInteger('store_id')->default(0);
            $table->integer('date_type')->nullable();
            $table->date('date')->nullable();
            $table->string('date_each_year',5)->nullable(); // Ngày tháng diễn ra 
            $table->longText('services')->nullable(); 

            $table->integer('sort_order')->nullable();
            
            $table->bigInteger('updater_id')->nullable(); // Update by user_id.
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
      Schema::dropIfExists('news_to_categories');
      Schema::dropIfExists('news');
    }
}
