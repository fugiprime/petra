<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNetworkTVShowTable extends Migration
{
    public function up()
    {
        Schema::create('network_tv_show', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('network_id');
            $table->unsignedBigInteger('tv_show_id');
            $table->timestamps();

            $table->foreign('network_id')->references('id')->on('networks')->onDelete('cascade');
            $table->foreign('tv_show_id')->references('id')->on('tv_shows')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('network_tv_show');
    }
}
;
