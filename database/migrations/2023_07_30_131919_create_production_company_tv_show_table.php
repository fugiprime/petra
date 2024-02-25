<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductionCompanyTVShowTable extends Migration
{
    public function up()
    {
        Schema::create('production_company_tv_show', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('production_company_id');
            $table->unsignedBigInteger('tv_show_id');
            $table->timestamps();

            $table->foreign('production_company_id')->references('id')->on('production_companies')->onDelete('cascade');
            $table->foreign('tv_show_id')->references('id')->on('tv_shows')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('production_company_tv_show');
    }
};
