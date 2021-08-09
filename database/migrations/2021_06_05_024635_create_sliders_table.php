<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSlidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sliders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title_en');
            $table->string('title_ar');
            $table->string('image');
            $table->unsignedBigInteger('restaurant_id');
            $table->unsignedBigInteger('product_id');
            // $table->string('type')->nullable();
            // $table->string('url')->nullable();
            // $table->string('size')->nullable();
            // $table->string('path')->nullable();
            // $table->unsignedBigInteger('user_id');
           
            $table->timestamps();
            $table->foreign('restaurant_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('terms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sliders');
    }
}
