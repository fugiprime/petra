<?php

// database/migrations/YYYY_MM_DD_HHmmss_create_cast_movie_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCastMovieTable extends Migration
{
    public function up()
    {
        Schema::create('cast_movie', function (Blueprint $table) {
            $table->unsignedBigInteger('cast_id');
            $table->unsignedBigInteger('movie_id');

            $table->foreign('cast_id')->references('id')->on('casts')->onDelete('cascade');
            $table->foreign('movie_id')->references('id')->on('movies')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cast_movie');
    }
}
;
