@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <h1>{{ $tvShow->name }}</h1>
                <p>{{ $tvShow->overview }}</p>
                <p>First Air Date: {{ $tvShow->first_air_date }}</p>
                <p>Status: {{ $tvShow->status }}</p>
                <p>Vote Average: {{ $tvShow->vote_average }}</p>
                
                <h2>Genres:</h2>
                <ul>
                    @foreach ($genres as $genre)
                        <li>{{ $genre->name }}</li>
                    @endforeach
                </ul>
                
                <h2>Production Companies:</h2>
                <ul>
                    @foreach ($productionCompanies as $company)
                        <li>{{ $company->name }}</li>
                    @endforeach
                </ul>
                
                <h2>Networks:</h2>
                <ul>
                    @foreach ($networks as $network)
                        <li>{{ $network->name }}</li>
                    @endforeach
                </ul>
                
                <h2>Seasons:</h2>
                <ul>
                    @foreach ($seasons as $season)
                        <li>
                            <strong>Season {{ $season->season_number }}</strong><br>
                            Air Date: {{ $season->air_date }}<br>
                            Episode Count: {{ $season->episode_count }}
                            <ul>
                                @foreach ($season->episodes as $episode)
                                    <li>
                                        <a href="{{ route('tvshows.episode', ['tvshowId' => $tvShow->id, 'seasonNumber' => $season->season_number, 'episodeNumber' => $episode->episode_number]) }}">{{ $episode->name }}</a><br>
                                        Episode Number: {{ $episode->episode_number }}<br>
                                        Air Date: {{ $episode->air_date }}<br>
                                        Runtime: {{ $episode->runtime }} minutes
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="col-md-4">
                <img src="https://www.themoviedb.org/t/p/w600_and_h900_bestv2/{{ $tvShow->poster_path }}" alt="{{ $tvShow->name }}" class="img-fluid">
            </div>
        </div>
        
        <h2>Cast:</h2>
        <ul>
            @foreach ($casts as $cast)
                <li>
                    {{ $cast->name }}
                </li>
            @endforeach
        </ul>
        
        <h2>Crew:</h2>
        <ul>
            @foreach ($crews as $crew)
                <li>
                    {{ $crew->name }} - {{ $crew->job }}
                </li>
            @endforeach
        </ul>
    </div>
@endsection
