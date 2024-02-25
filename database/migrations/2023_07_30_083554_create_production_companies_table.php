<?php

// create_production_companies_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductionCompaniesTable extends Migration
{
    public function up()
    {
        Schema::create('production_companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            // ... add any other columns you need for production companies
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('production_companies');
    }
};
