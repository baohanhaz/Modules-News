<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsDescriptionsTranslationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news_descriptions_translation', function (Blueprint $table) {
          $table->string('des_id',128);
          $table->text('name')->nullable();
          $table->longText('description')->nullable();
          $table->string('locale')->index();

          $table->unique(['des_id','locale']);
          //FOREIGN KEY CONSTRAINTS
          $table->foreign('des_id')->references('id')->on('news_descriptions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::dropIfExists('news_descriptions_translation');
    }
}
