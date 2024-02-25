<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
    protected $fillable = [
        'tv_show_season_id',
        'season_number',
        'air_date',
        'episode_number',
        'name',
        'overview',
        'runtime',
    ];

    // Define the relationship between episodes and TV show seasons
    public function season()
    {
        return $this->belongsTo(TVShowSeason::class, 'tv_show_season_id');
    }

    // Add any other custom logic or methods related to episodes as needed
    // ...
}

