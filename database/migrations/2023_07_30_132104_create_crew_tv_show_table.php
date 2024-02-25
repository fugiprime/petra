<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCrewTVShowTable extends Migration
{
    public function up()
    {
        Schema::create('crew_tv_show', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('crew_id');
            $table->unsignedBigInteger('tv_show_id');
            $table->timestamps();

            $table->foreign('crew_id')->references('id')->on('crews')->onDelete('cascade');
            $table->foreign('tv_show_id')->references('id')->on('tv_shows')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('crew_tv_show');
    }
};
