<?php

// database/migrations/YYYY_MM_DD_HHmmss_create_casts_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCastsTable extends Migration
{
    public function up()
    {
        Schema::create('casts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('profile_path')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('casts');
    }
};
