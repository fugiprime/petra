<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoviesTable extends Migration
{
    public function up()
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('status')->nullable();
            $table->string('release_date')->nullable();
            $table->text('overview')->nullable();
            $table->string('poster_path')->nullable();
            $table->boolean('adult')->default(false);
            $table->string('imdb_id')->nullable();
            $table->string('original_title')->nullable();
            $table->float('vote_average')->nullable();
            $table->integer('tmdb_id')->unique()->unsigned();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('movies');
    }
};
