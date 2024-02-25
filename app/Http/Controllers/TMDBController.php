<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\Movie;
use App\Models\Genre;
use App\Models\Cast;
use App\Models\Crew;
use App\Models\ProductionCompany;
use App\Models\TVShow;
use App\Models\Network;
use App\Models\TVShowSeason;
use App\Models\Episode;



class TMDBController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');

        // Make a request to TMDB API for search results
        $client = new Client();
        $apiKey = '570427250b38a9eb958ae8205ab98793';
        $language = 'en-US';

        $response = $client->request('GET', 'https://api.themoviedb.org/3/search/multi', [
            'query' => [
                'api_key' => $apiKey,
                'language' => $language,
                'query' => $query
            ]
        ]);

        $results = json_decode($response->getBody()->getContents())->results;

        // Process the search results as needed

        return view('search-results', ['results' => $results]);
    }

    public function saveMediaBackground(Request $request)
    {
        $id = $request->input('id');
        $mediaType = $request->input('mediaType');
        $clickedId = $request->input('clickedId');
        $clickedMediaType = $request->input('clickedMediaType');

        if ($id !== $clickedId || $mediaType !== $clickedMediaType) {
            $this->saveMedia($id, $mediaType);
        }

        return response()->json(['message' => 'Media data saved in the background.']);
    }

    public function form()
    {
        return view('searchform');
    }

    public function showMovie($movieId)
    {
        // Fetch the movie data from the database based on the $movieId
        $movie = Movie::find($movieId);

        // Add any additional logic or error handling if required
        // ...

        // Pass the movie data to the show.blade.php view
        return view('movies.show', ['movie' => $movie]);
    }

    public function saveMedia(Request $request, $id, $mediaType)
    {
        $media = Movie::where('tmdb_id', $id)->first();
        $media = TVShow::where('tmdb_id', $id)->first();
        // If the media exists, retrieve it from the database and avoid saving it again
        if ($media) {
            // Redirect to the movie show blade (or the respective show blade for TV shows)
            if ($mediaType === 'movie') {
                return redirect()->route('movies.show', ['movie' => $media->id]);
            } elseif ($mediaType === 'tv') {
                // Redirect to the TV show show blade
                return redirect()->route('tvshows.show', ['tv' => $media->id]);
            } else {
                // Redirect to a default page or handle other media types accordingly
                // ...
            }
        }
        $movieData = $this->fetchMediaDataFromTMDB($id, $mediaType);
        $tvShowData = $this->fetchMediaDataFromTMDB($id, $mediaType);

        // Save or update the media (movie or TV show) data in the database
        if ($mediaType === 'movie') {
            $media = Movie::updateOrCreate(
                ['id' => $movieData['id']],
                [
                    'title' => $movieData['title'],
                    'status' => $movieData['status'],
                    'release_date' => substr($movieData['release_date'], 0, 4), // Get the year from the full date
                    'overview' => $movieData['overview'],
                    'poster_path' => $movieData['poster_path'],
                    'adult' => $movieData['adult'],
                    'imdb_id' => $movieData['imdb_id'],
                    'original_title' => $movieData['original_title'],
                    'vote_average' => $movieData['vote_average'],
                    'tmdb_id' => $movieData['id'],
                ]
            );
        } elseif ($mediaType === 'tv') {
            $media = TVShow::updateOrCreate(
                ['id' => $id], // Use TMDB ID as the lookup key
                [
                    'adult' => $tvShowData['adult'],
                    'episode_run_time' => json_encode($tvShowData['episode_run_time']),
                    'first_air_date' => $tvShowData['first_air_date'],
                    'homepage' => $tvShowData['homepage'],
                    'name' => $tvShowData['name'],
                    'original_language' => $tvShowData['original_language'],
                    'original_name' => $tvShowData['original_name'],
                    'overview' => $tvShowData['overview'],
                    'poster_path' => $tvShowData['poster_path'],
                    'status' => $tvShowData['status'],
                    'type' => $tvShowData['type'],
                    'vote_average' => $tvShowData['vote_average'],
                    'tmdb_id' => $tvShowData['id'],
                ]
            );

            // Save TV show networks to the database and attach them to the TV show
            if (isset($tvShowData['networks']) && is_array($tvShowData['networks'])) {
                foreach ($tvShowData['networks'] as $networkData) {
                    $network = Network::updateOrCreate(
                        ['id' => $networkData['id']],
                        [
                            'name' => $networkData['name'],
                        ]
                    );
                    $media->networks()->attach($network);
                }
            }

            // Save TV show seasons to the database and attach them to the TV show
            if (isset($tvShowData['seasons']) && is_array($tvShowData['seasons'])) {
                foreach ($tvShowData['seasons'] as $seasonData) {
                    if ($seasonData['season_number'] === 0) {
                        continue;
                    }
                    $season = TVShowSeason::updateOrCreate(
                        ['id' => $seasonData['id']],
                        [
                            'name' => $seasonData['name'],
                            'tv_show_id' => $media->id,
                            'season_id' => $seasonData['id'], // Save the TMDB season ID
                            'air_date' => $seasonData['air_date'],
                            'episode_count' => $seasonData['episode_count'],
                            'overview' => $seasonData['overview'],
                            'poster_path' => $seasonData['poster_path'],
                            'season_number' => $seasonData['season_number'],
                            'vote_average' => $seasonData['vote_average'],
                        ]
                    );
                    $media->seasons()->save($season);
                    $this->saveEpisodesForSeason($id, $seasonData['season_number']);
    
                }
            }
        } else {
            // Handle other media types, if needed
            // ...
        }

        // Save genres to the database and attach them to the media (movie or TV show)
        foreach ($movieData['genres'] as $genreData) {
            $genre = Genre::updateOrCreate(['id' => $genreData['id']], ['name' => $genreData['name']]);
            $media->genres()->attach($genre);
        }

        // Save production companies to the database and attach them to the media (movie or TV show)
        foreach ($movieData['production_companies'] as $companyData) {
            $company = ProductionCompany::updateOrCreate(['id' => $companyData['id']], ['name' => $companyData['name']]);
            $media->productionCompanies()->attach($company);
        }

        // Save the credits for the media (if any)
        if ($mediaType === 'movie') {
            $this->saveCreditsFromTMDB($id, $mediaType, $media); // Pass $media to the method
        } elseif ($mediaType === 'tv') {
            $this->saveCreditsForTVShowFromTMDB($id, $mediaType, $media); // Pass $media to the method
        } else {
        // Handle other media types, if needed
        // ...
        }   
        // Redirect to the media (movie or TV show) show blade after saving
        if ($mediaType === 'movie') {
            return redirect()->route('movies.show', ['movie' => $media->id]);
        } elseif ($mediaType === 'tv') {
            // Redirect to the TV show show blade
            return redirect()->route('tvshows.show', ['tv' => $media->id]);

        } else {
            // Redirect to a default page or handle other media types accordingly
            // ...
        }
    }

    private function fetchCreditsDataFromTMDB($id, $mediaType)
    {
        $client = new Client();

        try {
            $response = $client->request('GET', "https://api.themoviedb.org/3/{$mediaType}/{$id}/credits", [
                'query' => [
                    'api_key' => '570427250b38a9eb958ae8205ab98793',
                ],
            ]);

            $creditsData = json_decode($response->getBody()->getContents(), true);

            return $creditsData;
        } catch (ClientException $e) {
            // Handle the exception (e.g., log the error, display a message, etc.)
            // For simplicity, we'll just return an empty array for now.
            return [];
        }
    }

    private function saveCreditsFromTMDB($id, $mediaType, $media)
    {
        // Fetch the credits data from the TMDB API
        $creditsData = $this->fetchCreditsDataFromTMDB($id, $mediaType);

        // Save the cast members to the database
        if (isset($creditsData['cast']) && is_array($creditsData['cast'])) {
            foreach ($creditsData['cast'] as $castData) {
                $cast = Cast::updateOrCreate(
                    ['id' => $castData['id']],
                    [
                        'id' => $castData['id'],
                        'name' => $castData['name'],
                        'profile_path' => $castData['profile_path'],
                    ]
                );
                $media->casts()->attach($cast); // Attach the cast to the movie
            }
        }

        // Save the crew members to the database
        if (isset($creditsData['crew']) && is_array($creditsData['crew'])) {
            foreach ($creditsData['crew'] as $crewData) {
                $crew = Crew::updateOrCreate(
                    ['id' => $crewData['id']],
                    [
                        'id' => $crewData['id'],
                        'name' => $crewData['name'],
                        'job' => $crewData['job'],
                        'profile_path' => $crewData['profile_path'],
                    ]
                );
                $media->crews()->attach($crew); // Attach the crew to the movie
            }
        }
    }

    private function saveCreditsForTVShowFromTMDB($id, $mediaType, $media)
{
    // Fetch the credits data from the TMDB API for TV shows
    $creditsData = $this->fetchCreditsDataForTVShowFromTMDB($id);

    // Save the cast members to the database
    if (isset($creditsData['cast']) && is_array($creditsData['cast'])) {
        foreach ($creditsData['cast'] as $castData) {
            $cast = Cast::updateOrCreate(
                ['id' => $castData['id']],
                [
                    'id' => $castData['id'],
                    'name' => $castData['name'],
                    'profile_path' => $castData['profile_path'],
                ]
            );
            $media->casts()->attach($cast); // Attach the cast to the TV show
        }
    }

    // Save the crew members to the database
   // Save the crew members to the database
   if (isset($creditsData['crew']) && is_array($creditsData['crew'])) {
    foreach ($creditsData['crew'] as $crewData) {
        $crewAttributes = [
            'id' => $crewData['id'],
            'name' => $crewData['name'],
            'profile_path' => $crewData['profile_path'],
        ];

        // Check if the "jobs" key exists and if it contains at least one job entry
        if (isset($crewData['jobs']) && is_array($crewData['jobs']) && count($crewData['jobs']) > 0) {
            // For simplicity, we'll take the first job entry from the "jobs" array
            $jobEntry = $crewData['jobs'][0];

            // Check if the "job" key exists within the job entry before accessing it
            if (isset($jobEntry['job'])) {
                $crewAttributes['job'] = $jobEntry['job'];
            }
        }
        $crew = Crew::updateOrCreate(['id' => $crewData['id']], $crewAttributes);
        $media->crews()->attach($crew); // Attach the crew to the movie or TV show
    }
}
}

private function fetchCreditsDataForTVShowFromTMDB($id)
{
    $client = new Client();

    try {
        $response = $client->request('GET', "https://api.themoviedb.org/3/tv/{$id}/aggregate_credits", [
            'query' => [
                'api_key' => '570427250b38a9eb958ae8205ab98793',
            ],
        ]);

        $creditsData = json_decode($response->getBody()->getContents(), true);

        return $creditsData;
    } catch (ClientException $e) {
        // Handle the exception (e.g., log the error, display a message, etc.)
        // For simplicity, we'll just return an empty array for now.
        return [];
    }
}

    private function fetchMediaDataFromTMDB($id, $mediaType)
    {
        $client = new Client();
        $apiKey = '570427250b38a9eb958ae8205ab98793';
        $language = 'en-US';

        // Make a request to TMDB API to fetch media data based on the ID and media type
        $response = $client->request('GET', "https://api.themoviedb.org/3/{$mediaType}/{$id}", [
            'query' => [
                'api_key' => $apiKey,
                'language' => $language,
            ]
        ]);

        // Process the response and return the media data as an associative array
        return json_decode($response->getBody()->getContents(), true);
    }


    // Add this method to your TMDBController to fetch and save episodes for a specific season
    private function saveEpisodesForSeason($tvShowId, $seasonNumber)
    {
        $client = new Client();
        $apiKey = '570427250b38a9eb958ae8205ab98793'; // Replace with your actual TMDB API key
        $language = 'en-US';
    
        try {
            $response = $client->request('GET', "https://api.themoviedb.org/3/tv/{$tvShowId}/season/{$seasonNumber}", [
                'query' => [
                    'api_key' => $apiKey,
                    'language' => $language,
                ],
            ]);
    
            $seasonData = json_decode($response->getBody()->getContents(), true);
    
            // Find the TVShowSeason based on the conditions (Assuming 'tmdb_id' is used as the lookup key)
            $tvShowSeason = TVShowSeason::where('season_id', $seasonData['id'])->first();
    
            if ($tvShowSeason) {
                $episodesData = $seasonData['episodes'] ?? [];
                foreach ($episodesData as $episodeData) {
                    // Save or update the episode data in the database
                    // Replace 'Episode' with the actual model for your episodes table
                    Episode::updateOrCreate(
                        ['id' => $episodeData['id']], // Use TMDB episode ID as the lookup key
                        [
                            'air_date' => $episodeData['air_date'],
                            'episode_number' => $episodeData['episode_number'],
                            'name' => $episodeData['name'],
                            'overview' => $episodeData['overview'],
                            'runtime' => $episodeData['runtime'],
                            'season_number' => $episodeData['season_number'],
                            'tv_show_season_id' => $tvShowSeason->id, // Use the id from the TVShowSeason model
                        ]
                    );
                }
            }
        } catch (ClientException $e) {
            // Handle the exception (e.g., log the error, display a message, etc.)
            // For simplicity, we'll just ignore any errors here.
        }
    } 

}
