<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Network extends Model
{
    protected $fillable = ['name'];

    public function tvShows()
    {
        return $this->belongsToMany(TVShow::class, 'network', 'network_id', 'tv_show_id');
    }
}