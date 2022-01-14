<?php

namespace App\Http\Controllers;
use App\Models\Track;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('index')->with('Tracks',Track::all());
    }
}
