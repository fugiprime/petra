<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Crew extends Model
{
    protected $fillable = ['name', 'job', 'profile_path'];
    
    // Other model definitions...

    public function movies()
    {
        return $this->belongsToMany(Movie::class);
    }

    public function tvShows()
{
    return $this->belongsToMany(TVShow::class, 'tv_show_' . strtolower(class_basename($this)), strtolower(class_basename($this)) . '_id', 'tv_show_id');
}
}
