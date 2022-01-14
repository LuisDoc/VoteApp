<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TrackController;

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

//Startseite
Route::get('/',[HomeController::class,'index']);

//UP & DOWN Voting
Route::get('/upvote/{id}',[TrackController::class,'upvote']);
Route::get('/downvote/{id}',[TrackController::class,'downvote']);

//Create Task
Route::get('/addTrack',[TrackController::class,'addForm']);
Route::post('/createTrack',[TrackController::class,'createTrack']);