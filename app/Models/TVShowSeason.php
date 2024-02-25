<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TVShowSeason extends Model
{
    protected $table = 'tv_show_seasons';
    protected $fillable = [
        'name',
        'tv_show_id',
        'season_id',
        'air_date',
        'episode_count',
        'overview',
        'poster_path',
        'season_number',
        'vote_average',
    ];

    public function tvShow()
    {
        return $this->belongsTo(TVShow::class);
    }
    
    public function episodes()
    {
        return $this->hasMany(Episode::class, 'tv_show_season_id');
    }
}
