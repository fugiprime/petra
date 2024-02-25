@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $movie->title }}</h1>
        <p>Status: {{ $movie->status }}</p>
        <p>Release Date: {{ $movie->release_date }}</p>
        <p>Overview: {{ $movie->overview }}</p>
        <p>Vote Average: {{ $movie->vote_average }}</p>
        <img src="https://www.themoviedb.org/t/p/w600_and_h900_bestv2/{{ $movie->poster_path }}" alt="Movie Poster" class="img-fluid">
        <p>Genres:</p>
        <ul>
            @foreach ($movie->genres as $genre)
                <li>{{ $genre->name }}</li>
            @endforeach
        </ul>
        <p>Production Companies:</p>
        <ul>
            @foreach ($movie->productionCompanies as $company)
                <li>{{ $company->name }}</li>
            @endforeach
        </ul>
        <p>Cast:</p>
        <ul>
            @foreach ($movie->casts as $cast)
                <li>{{ $cast->name }} </li>
            @endforeach
        </ul>
    </div>
@endsection
