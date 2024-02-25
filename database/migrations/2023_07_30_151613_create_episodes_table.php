<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEpisodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('episodes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tv_show_season_id');
            $table->date('air_date')->nullable();
            $table->integer('episode_number')->default(0);
            $table->string('name')->nullable();
            $table->text('overview')->nullable();
            $table->integer('runtime')->nullable();
            $table->integer('season_number')->default(0);
            $table->timestamps();

            // Define foreign key constraint to link episodes with TV show seasons
            $table->foreign('tv_show_season_id')->references('id')->on('tv_show_seasons')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('episodes');
    }
};
