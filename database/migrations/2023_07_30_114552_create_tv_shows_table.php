<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTvShowsTable extends Migration
{
    public function up()
    {
        Schema::create('tv_shows', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('poster_path')->nullable();
            $table->text('overview')->nullable();
            $table->date('first_air_date')->nullable();
            $table->float('vote_average')->nullable();
            $table->integer('vote_count')->nullable();
            $table->string('status')->nullable();
            $table->boolean('adult')->default(false);
            $table->integer('tmdb_id')->unique()->unsigned();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tv_shows');
    }
};
