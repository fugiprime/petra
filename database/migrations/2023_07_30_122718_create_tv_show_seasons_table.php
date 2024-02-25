<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('tv_show_seasons', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('tv_show_id');
        $table->string('season_id')->unique();
        $table->date('air_date')->nullable();
        $table->integer('episode_count')->default(0);
        $table->string('name')->nullable();
        $table->text('overview')->nullable();
        $table->string('poster_path')->nullable();
        $table->integer('season_number')->default(0);
        $table->float('vote_average')->default(0);
        $table->timestamps();

        $table->foreign('tv_show_id')->references('id')->on('tv_shows')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tv_show_seasons');
    }
};
