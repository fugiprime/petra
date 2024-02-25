<?php

// Movie.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $fillable = [
        'title', 'status', 'release_date', 'overview', 'poster_path', 'adult',
        'imdb_id', 'original_title', 'vote_average', 'tmdb_id',
    ];

    // Define the many-to-many relationship with genres
    public function genres()
    {
        return $this->belongsToMany(Genre::class);
    }

    // Define the many-to-many relationship with production companies
    public function productionCompanies()
    {
        return $this->belongsToMany(ProductionCompany::class);
    }

    // Define the many-to-many relationship with casts (actors)
    public function casts()
    {
        return $this->belongsToMany(Cast::class);
    }

    public function crews()
    {
        return $this->belongsToMany(Crew::class);
    }

}

