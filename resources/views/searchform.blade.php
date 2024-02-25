@extends('layouts.app')

@section('content')
   
<div class="container-fluid main-container">
    <div class="row">
        <div class="col-md-6 offset-md-3 text-center search-section">
            <h1>Welcome to our Movie & TV Show Portal</h1>
            <form action="{{ route('search') }}" method="GET" class="search-form">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="query" placeholder="Search TMDB...">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">Search</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 offset-md-3 content-section">
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium.</p>
            <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident.</p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 offset-md-3 text-center button-section">
            <a href="#" class="btn btn-secondary">Back to Main Page</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 offset-md-3 text-center icon-section">
            <!-- Add your icons here -->
        </div>
    </div>
</div>

    <style>
        .main-container {
            padding-top: 50px;
        }

        .search-section {
            margin-bottom: 30px;
        }

        .content-section {
            margin-bottom: 30px;
        }

        .button-section {
            margin-bottom: 30px;
        }

        .icon-section {
            margin-bottom: 50px;
        }
    </style>
@endsection
