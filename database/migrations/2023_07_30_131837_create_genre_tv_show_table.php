<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGenreTVShowTable extends Migration
{
    public function up()
    {
        Schema::create('genre_tv_show', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('genre_id');
            $table->unsignedBigInteger('tv_show_id');
            $table->timestamps();

            $table->foreign('genre_id')->references('id')->on('genres')->onDelete('cascade');
            $table->foreign('tv_show_id')->references('id')->on('tv_shows')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('genre_tv_show');
    }
};
