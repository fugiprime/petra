<?php

// ProductionCompany.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model
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
        return $this->belongsToMany(TVShow::class, 'tv_show_' . strtolower(class_basename($this)), strtolower(class_basename($this)) . '_id', 'tv_show_id');
    }
}

