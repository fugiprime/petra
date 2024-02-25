<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TvShow;
use App\Models\Episode;

class TvShowController extends Controller
{

    public function showEpisode($tvshowId, $seasonNumber, $episodeNumber)
{
    $tvShow = TvShow::find($tvshowId);
    
    // Find the corresponding season based on the season number and the TV show's relationship
    $season = $tvShow->seasons()->where('season_number', $seasonNumber)->first();
    
    if ($season) {
        // Find the corresponding episode based on the episode number and the season's relationship
        $episode = $season->episodes()->where('episode_number', $episodeNumber)->first();
    }

    // You can pass both the $episode and $tvShow variables to the episode.blade.php view
    return view('tvshows.episode', compact('episode', 'tvShow'));
}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        {
            // Fetch the TV show data from the database based on the $id
            $tvShow = TVShow::findOrFail($id);
    
            // Fetch the genres, production companies, networks, and seasons related to the TV show
            $genres = $tvShow->genres;
            $productionCompanies = $tvShow->productionCompanies;
            $networks = $tvShow->networks;
            $seasons = $tvShow->seasons;
    
            // Fetch the cast and crew for the TV show
            $casts = $tvShow->casts;
            $crews = $tvShow->crews;
    
            return view('tvshows.show', [
                'tvShow' => $tvShow,
                'genres' => $genres,
                'productionCompanies' => $productionCompanies,
                'networks' => $networks,
                'seasons' => $seasons,
                'casts' => $casts,
                'crews' => $crews,
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
