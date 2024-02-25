<?php

// ProductionCompany.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionCompany extends Model
{
    protected $fillable = ['name'];

    // Define the many-to-many relationship with movies
    public function movies()
    {
        return $this->belongsToMany(Movie::class);
    }

    // Define the many-to-many relationship with TV shows
    public function tvShows()
    {
        return $this->belongsToMany(TVShow::class);
    }
}

