<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TrackController;
use App\Http\Controllers\Auth\LoginController;
use RealRashid\SweetAlert\Facades\Alert;
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

Auth::routes();

//Startseite
Route::get('/',[HomeController::class,'index']);
Route::get('/home',[HomeController::class,'index']);
Route::get('/testSpotify',[TrackController::class, 'testSpotify'])->middleware('auth');

//UP & DOWN Voting
Route::get('/upvote/{id}',[TrackController::class,'upvote'])->middleware('auth');
Route::get('/downvote/{id}',[TrackController::class,'downvote'])->middleware('auth');

//Voting Ergebnisse Kontrollieren
Route::get('checkResults/{id}',[TrackController::class,'checkResults'])->middleware('auth');

//Create Track
Route::get('/createTrack/{id}',[TrackController::class,'createTrack'])->middleware('auth');
Route::get('/addTrack',[TrackController::class,'addForm'])->middleware('auth');
Route::post('/searchTrack',[TrackController::class,'searchTrack'])->middleware('auth');

//Auth

Route::get('/logout', [LoginController::class,'logout']);
Auth::routes();


/*
    Spotify Web-Api Authorization
*/
Route::get('/callback',[TrackController::class,'fetchToken']);
Route::get('/authSpotify', [TrackController::class, 'authSpotify']);