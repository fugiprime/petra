@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Search Results</h1>

        <div class="row">
            @foreach ($results as $result)
                <div class="col-md-2 mb-4">
                    <div class="result-item">
                        @if (isset($result->poster_path))
                        <a href="{{ route('saveMedia', ['id' => $result->id, 'mediaType' => $result->media_type]) }}">
                                <img src="https://image.tmdb.org/t/p/w500/{{ $result->poster_path }}" alt="Poster" class="img-fluid">
                            </a>
                        @endif
                        @if ($result->media_type === 'tv')
                            <h5><a href="{{ route('saveMedia', ['id' => $result->id, 'mediaType' => 'tv']) }}"> {{ $result->name }}</a></h5>
                            @if (isset($result->first_air_date))
                                <p>Release Year: {{ \Carbon\Carbon::parse($result->first_air_date)->format('Y') }}</p>
                            @endif
                        @elseif ($result->media_type === 'movie')
                            <h5><a href="{{ route('saveMedia', ['id' => $result->id, 'mediaType' => 'movie']) }}">{{ $result->title }}</a></h5>
                            @if (isset($result->release_date))
                                <p>Release Year: {{ \Carbon\Carbon::parse($result->release_date)->format('Y') }}</p>
                            @endif
                        @endif
                    </div>
                </div>

                @if ($loop->iteration % 5 === 0)
                    </div>
                    <div class="row">
                @endif
            @endforeach
        </div>
    </div>
@endsection
