<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TVShow extends Model
{
    protected $table = 'tv_shows';

    protected $fillable = [
        'name',
        'poster_path',
        'overview',
        'first_air_date',
        'vote_average',
        'vote_count',
        'status',
        'adult',
        'tmdb_id',
    ];
    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'genre_tv_show', 'tv_show_id', 'genre_id');
    }

    public function productionCompanies()
    {
        return $this->belongsToMany(ProductionCompany::class, 'production_company_tv_show', 'tv_show_id', 'production_company_id');
    }

    public function networks()
    {
        return $this->belongsToMany(Network::class, 'network_tv_show', 'tv_show_id', 'network_id');
    }

    public function seasons()
    {
        return $this->hasMany(TVShowSeason::class, 'tv_show_id');
    }

    public function casts()
    {
        return $this->belongsToMany(Cast::class, 'cast_tv_show', 'tv_show_id', 'cast_id');
    }

    public function crews()
    {
        return $this->belongsToMany(Crew::class, 'crew_tv_show', 'tv_show_id', 'crew_id');
    }
}
