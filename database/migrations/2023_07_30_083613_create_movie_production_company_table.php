<?php

// create_movie_production_company_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovieProductionCompanyTable extends Migration
{
    public function up()
    {
        Schema::create('movie_production_company', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('movie_id');
            $table->unsignedBigInteger('production_company_id');

            $table->foreign('movie_id')->references('id')->on('movies')->onDelete('cascade');
            $table->foreign('production_company_id')->references('id')->on('production_companies')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('movie_production_company');
    }
};
