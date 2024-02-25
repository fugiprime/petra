<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', 'App\Http\Controllers\MovieController@form');
//Route::post('/movies/save', 'App\Http\Controllers\MovieController@store');
//Route::get('/movies', 'App\Http\Controllers\MovieController@index');
//Route::get('/search', 'App\Http\Controllers\TMDBController@search')->name('search');
//Route::post('/save', 'App\Http\Controllers\TMDBController@save')->name('save');
//Route::get('/movies/{id}', 'App\Http\Controllers\MovieController@show')->name('movies.show');
//Auth::routes();
//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/search', 'App\Http\Controllers\TMDBController@search')->name('search');
Route::get('/', 'App\Http\Controllers\TMDBController@form')->name('form');
Route::get('/save-media/{id}/{mediaType}', 'App\Http\Controllers\TMDBController@saveMedia')->name('saveMedia');
Route::get('/movies/{movie}', 'App\Http\Controllers\MovieController@show')->name('movies.show');
Route::get('tv/{tv}', 'App\Http\Controllers\TVShowController@show')->name('tvshows.show');
Route::get('/tv/{tvshowId}/-season-{seasonNumber}-episode-{episodeNumber}', 'App\Http\Controllers\TvShowController@showEpisode')->name('tvshows.episode');






