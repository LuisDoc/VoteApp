<?php

namespace App\Http\Controllers;

use App\Models\Track;
use Illuminate\Http\Request;

class TrackController extends Controller
{
    /*
    Trackverwaltung
    Tracks hinzufügen
    Tracks entfernen
    Abstimmungen beenden
    */
    public function addForm(Request $request){
        return view('addTrack');
    }
    public function createTrack(Request $request){
        $Track = new Track;
        $Track->fill([
            'name' => $request->trackname,
            'voteCommit' => 0,
            'voteDiscard' => 0
        ]);
        $Track->save();
        return redirect('/');
    }
    /*
    Voting System
    Methoden für Up und Down Votes
    */
    public function upvote(Request $request){
        $Track = Track::find($request->id);
        $upvotes = $Track->voteCommit + 1;
        $Track->voteCommit = $upvotes;
        $Track->save();

        return redirect()->back();
    }
    public function downvote(Request $request){
        $Track = Track::find($request->id);
        $downvotes = $Track->voteDiscard + 1;
        $Track->voteDiscard = $downvotes;
        $Track->save();

        return redirect()->back();
    }
    public function checkResults(){
        return;
    }
}
