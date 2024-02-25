<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Command;

class SaveMediaDataCommand extends Command
{
    protected $signature = 'save:media {startId : The starting TMDb ID} {batchSize : Number of items to process in each batch}';

    protected $description = 'Save movie and TV show data in the background from TMDb';

    public function handle()
    {
        $startId = $this->argument('startId');
        $batchSize = $this->argument('batchSize');

        $client = new Client();
        $apiKey = 'YOUR_TMDB_API_KEY';
        $language = 'en-US';

        // Start asynchronous HTTP requests in batches
        for ($i = $startId; $i < $startId + $batchSize; $i++) {
            $url = "https://api.themoviedb.org/3/movie/{$i}?api_key={$apiKey}&language={$language}";
            $this->fetchAndSaveMediaData($client, $url);
        }
    }

    private function fetchAndSaveMediaData(Client $client, string $url)
    {
        $client->getAsync($url)
            ->then(function ($response) {
                $mediaData = json_decode($response->getBody(), true);
                $mediaType = $this->getMediaType($mediaData);

                if ($mediaType === 'movie') {
                    // Save movie data to the database
                    $this->saveMovieData($mediaData);
                } elseif ($mediaType === 'tv') {
                    // Save TV show data to the database
                    $this->saveTVShowData($mediaData);
                } else {
                    // Handle other media types if needed
                }
            })
            ->otherwise(function ($reason) {
                // Handle errors, if any
                // ...
            });
    }

    private function getMediaType(array $mediaData): ?string
    {
        if (isset($mediaData['media_type']) && $mediaData['media_type'] === 'tv') {
            return 'tv';
        } elseif (isset($mediaData['id'])) {
            return 'movie';
        }

        return null;
    }

    private function saveMovieData(array $movieData)
{
    // Save movie data to the database
    $movie = Movie::updateOrCreate(
        ['id' => $movieData['id']],
        [
            'title' => $movieData['title'],
            'status' => $movieData['status'],
            'release_date' => substr($movieData['release_date'], 0, 4),
            'overview' => $movieData['overview'],
            'poster_path' => $movieData['poster_path'],
            'adult' => $movieData['adult'],
            'imdb_id' => $movieData['imdb_id'],
            'original_title' => $movieData['original_title'],
            'vote_average' => $movieData['vote_average'],
            'tmdb_id' => $movieData['id'],
            // Add other movie-specific fields as needed
        ]
    );

    // Save genres to the database and attach them to the movie
    foreach ($movieData['genres'] as $genreData) {
        $genre = Genre::updateOrCreate(['id' => $genreData['id']], ['name' => $genreData['name']]);
        $movie->genres()->attach($genre);
    }

    // Save production companies to the database and attach them to the movie
    foreach ($movieData['production_companies'] as $companyData) {
        $company = ProductionCompany::updateOrCreate(['id' => $companyData['id']], ['name' => $companyData['name']]);
        $movie->productionCompanies()->attach($company);
    }

    // Save the credits for the movie (cast and crew)
    $this->saveCreditsFromTMDB($movieData['id'], 'movie', $movie);
}

private function saveTVShowData(array $tvShowData)
{
    // Save TV show data to the database
    $tvShow = TVShow::updateOrCreate(
        ['id' => $tvShowData['id']],
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
            // Add other TV show-specific fields as needed
        ]
    );

    // Save TV show networks to the database and attach them to the TV show
    foreach ($tvShowData['networks'] as $networkData) {
        $network = Network::updateOrCreate(['id' => $networkData['id']], ['name' => $networkData['name']]);
        $tvShow->networks()->attach($network);
    }

    // Save TV show seasons to the database and attach them to the TV show
    foreach ($tvShowData['seasons'] as $seasonData) {
        // Skip Season 0
        if ($seasonData['season_number'] == 0) {
            continue;
        }

        $season = TVShowSeason::updateOrCreate(
            ['id' => $seasonData['id']],
            [
                'name' => $seasonData['name'],
                'tv_show_id' => $tvShow->id,
                'season_id' => $seasonData['id'], // Save the TMDB season ID
                'air_date' => $seasonData['air_date'],
                'episode_count' => $seasonData['episode_count'],
                'overview' => $seasonData['overview'],
                'poster_path' => $seasonData['poster_path'],
                'season_number' => $seasonData['season_number'],
                'vote_average' => $seasonData['vote_average'],
            ]
        );

        $tvShow->seasons()->save($season);
        $this->saveEpisodesForSeason($tvShowData['id'], $seasonData['season_number']);
    }

    // Save the credits for the TV show (cast and crew)
    $this->saveCreditsForTVShowFromTMDB($tvShowData['id'], 'tv', $tvShow);
}
}