@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <h1>{{ $episode->name }}</h1>
                <p>Episode Number: {{ $episode->episode_number }}</p>
                <p>Air Date: {{ $episode->air_date }}</p>
                <p>Runtime: {{ $episode->runtime }} minutes</p>
                <p> Overview : {{$episode->overview}}</p>
                <!-- Other episode details -->
            </div>
            <div class="col-md-4">
                <img src="https://www.themoviedb.org/t/p/w600_and_h900_bestv2/{{ $tvShow->poster_path }}" alt="{{ $tvShow->name }}" class="img-fluid">
            </div>
        </div>
    </div>
@endsection
