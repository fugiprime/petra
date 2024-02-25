<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCastTVShowTable extends Migration
{
    public function up()
    {
        Schema::create('cast_tv_show', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cast_id');
            $table->unsignedBigInteger('tv_show_id');
            $table->timestamps();

            $table->foreign('cast_id')->references('id')->on('casts')->onDelete('cascade');
            $table->foreign('tv_show_id')->references('id')->on('tv_shows')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cast_tv_show');
    }
};
