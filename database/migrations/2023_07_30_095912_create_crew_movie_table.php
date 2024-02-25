<?php

// database/migrations/YYYY_MM_DD_HHmmss_create_crew_movie_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCrewMovieTable extends Migration
{
    public function up()
    {
        Schema::create('crew_movie', function (Blueprint $table) {
            $table->unsignedBigInteger('crew_id');
            $table->unsignedBigInteger('movie_id');

            $table->foreign('crew_id')->references('id')->on('crews')->onDelete('cascade');
            $table->foreign('movie_id')->references('id')->on('movies')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('crew_movie');
    }
};
