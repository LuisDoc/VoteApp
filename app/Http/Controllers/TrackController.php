<?php

namespace App\Http\Controllers;

use App\Models\Track;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use Carbon\Carbon;
use Spotify;
use GuzzleHttp\Client;
use Cache;
use RealRashid\SweetAlert\Facades\Alert;

class TrackController extends Controller
{   
    //Client ID und Client Secret nicht verändern
    private $clientId = '5e992696a7724ade82439fe807f60ec6';
    private $clientSecret = 'adba76b0ae5e4b328faa3f968297814f';
    /*
        Ändere die folgenden Werte
    */
    private $playlist = '5lUNcbQ6ezhtgfmHSOcARP';

    

    public function authSpotify(){
        //Daten
        $redirect_uri = 'http://127.0.0.1:8000/callback';
        
        //Anlegen der URL
        $url = 'https://accounts.spotify.com/authorize';
        $url = $url.'?client_id='.$this->clientId;
        $url = $url.'&response_type=code';
        $url = $url.'&redirect_uri='.$redirect_uri;
        $url = $url.'&show_dialog=true';
        $url = $url.'&scope=playlist-modify-private playlist-modify-public user-read-private user-read-email user-modify-playback-state user-read-playback-position user-library-read streaming user-read-playback-state user-read-recently-played playlist-read-private';
        ($url);
       return redirect($url);
    }
    public function fetchToken(Request $request){
        /*
            Bestimmen Access Token
        */
        $url = 'https://accounts.spotify.com/api/token';
        $redirect_uri = 'http://127.0.0.1:8000/callback';
    
        
        $res = Http::withBasicAuth($this->clientId, $this->clientSecret)
        ->asForm()->post($url,
            [
                'grant_type' => 'authorization_code',
                'code' => $request->code,
                'redirect_uri' => $redirect_uri
            ]
        );        
       
        $tokenToJson = $res->json();
        $token = $tokenToJson['access_token']; 
        
        $track = Cache::pull('track');
        //Speicher bereinigen
        Cache::forget('track');

        if($track == ''){
            Alert::error('Fehler','Es ist ein Fehler vorgekommen. Bitte probier es doch nochmal');
            return redirect('/');
        }
         /*
            Hinzufügen des Tracks zur Playlist
        */
        $url = "https://api.spotify.com/v1/playlists/{$this->playlist}/tracks";
        
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://api.spotify.com/v1/playlists/'.$this->playlist.'/tracks?uris=spotify%3Atrack%3A'.$track);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
          
        $headers = array();
        $headers[] = 'Accept: application/json';
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: Bearer '.$token;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
          
        $result = curl_exec($ch);

        Alert::success('Erfolg','Der Track wird zur Playlist hinzugefügt');
        return redirect('/');
    }
    
    /*
    Weiterleitung zur Webseite, auf der Tracks gesucht und zum Voting hinzugefügt werden können
    */
    public function addForm(Request $request){
        return view('addTrack')
        ->with('suchtext','')
        ->with('searchResults',[]);
    }
    /*
    Der Nutzer gibt auf der AddTrack View einen Namen ein
    Search track berechnet mithilfe der Spotify API mögliche Ergebnisse und bietet sie zum hinzufügen an
    */
    public function searchTrack(Request $request){
        if($request->trackname){
            return view('addTrack')
            ->with('suchtext',$request->trackname)
            ->with('searchResults',Spotify::searchTracks($request->trackname)->get());
        }
        
        return view('addTrack')
        ->with('suchtext','')
        ->with('searchResults',[]);
    }
    /*
    Wenn der Nutzer sich für einen Track entschieden hat, wird mittels dieser funktion der Track zur DB hinzugefügt
    */
    public function createTrack(Request $request){
        //Validiere ob Track bereits in Datenbank enthalten ist
        if(Track::find($request->id)){
            Alert::error('Fehler','Der Track steht bereits zur Abstimmung');
           return redirect('/');
        }
        //Validiere ob Track bereits in Spotify Playlist enthalten ist
        // Get a playlist's tracks by ID.
        $Tracklist = Spotify::playlistTracks($this->playlist)->get();
        $found = false;
        foreach($Tracklist['items'] as $Track){
            if($Track['track']['id'] == $request->id){
                $found = true;
            }
        }
        //Wenn der Track bereits gefunden wurde
        if($found){
            Alert::error('Fehler','Der Track ist bereits in der Playlist');
            return redirect('/');
        }

        $TrackInfos = Spotify::track($request->id)->get();
        
        if($TrackInfos){
            $artists = "";

            foreach($TrackInfos['artists'] as $artist){
                $artists = $artists." ".$artist['name'];
            }

            $Track = new Track;
            $Track->id = $request->id;
            $Track->name = $TrackInfos['name']." - ".$artists;
            $Track->voteCommit = 0;
            $Track->voteDiscard = 0;
            $Track->save();
        }
       
        Alert::success('Erfolg','Der Track wurde zur Abstimmung freigegeben');
        return redirect('/');
    }
    /*
    Voting System
    Methoden für Up und Down Votes
    */
    //Upvoting und anschließende Ergebniskontrolle
    public function upvote(Request $request){
        //Validiere ob User bereits für Track abgestimmt hat
        if(Vote::where('track_id',$request->id)->where('user_id',Auth()->User()->id)->get()->count() > 0){
            Alert::error('Fehler','Du hast bereits für diesen Track abgestimmt');
            return redirect('/');
        }

        //Inkrementieren des Tracks
        $Track = Track::find($request->id);
        $upvotes = $Track->voteCommit + 1;
        $Track->voteCommit = $upvotes;
        //Registrieren des Votes
        $Vote = new Vote;
        $Vote->user_id = Auth()->User()->id;
        $Vote->track_id = $request->id;
        
        //Einträge speichern
        $Vote->save();
        $Track->save();

        //Kontrolle ob Vote abgeschlossen ist
        $AmountUser = User::all()->count();
        $Upvotes = Track::find($request->id)->voteCommit;
        $DownVotes = Track::find($request->id)->voteDiscard;
        
        Alert::success('Erfolg','Du hast erfolgreich abgestimmt');
        return redirect()->back();
    }
    //Downvoting und anschließende Ergebniskontrolle
    public function downvote(Request $request){
        //Validiere ob User bereits für Track abgestimmt hat
         if(Vote::where('track_id',$request->id)->where('user_id',Auth()->User()->id)->get()->count() > 0){
            Alert::error('Fehler','Du hast bereits für diesen Track abgestimmt');
            return redirect('/');
        }

        //Erstellen des Tracks
        $Track = Track::find($request->id);
        $downvotes = $Track->voteDiscard + 1;
        $Track->voteDiscard = $downvotes;
        $Track->save();

        //Registrieren des Votes
        $Vote = new Vote;
        $Vote->user_id = Auth()->User()->id;
        $Vote->track_id = $request->id;
        $Vote->save();

        //Kontrolle ob Vote abgeschlossen ist
        $AmountUser = User::all()->count();
        $Upvotes = Track::find($request->id)->voteCommit;
        $DownVotes = Track::find($request->id)->voteDiscard;

        Alert::success('Erfolg','Du hast erfolgreich abgestimmt');
        return redirect()->back();
    }

    /*
    Die CheckResults funktion wird über "Abstimmung beendet" Aufgerufen und wertet die Aktuellen Voting-Ergebnisse aus
    Hierbei kann die Meldung erscheinen, dass die Abstimmung aufgrund fehlender Votings noch nicht beendet ist,
    das die Abstimmung beendet wurde und das Lied aufgenommen wurde, oder das das Lied nicht aufgenommen wurde
    */
    public function checkResults(Request $request){
        //Informationen zur berechnung sammeln
        $Track = Track::find($request->id);
        $upvotes = $Track->voteCommit;
        $downvotes = $Track->voteDiscard;
        $AmountUser = User::all()->count();

        //Berechnen ob genug Leute abgestimmt haben
        if(($upvotes + $downvotes)== $AmountUser){
            //Mehr Upvotes wie Downvotes
            if($upvotes > $downvotes){
                //Track Daten in Chache packen
                $expiresAt = Carbon::now()->addMinutes(1);
                Cache::put('track', $request->id, $expiresAt);  
                //Weiter zur Auth
                //Votes aus Datenbank löschen
                Vote::where('track_id',$request->id)->delete();
                //Track aus Datenbank löschen
                Track::where('id',$request->id)->delete();
                return redirect('/authSpotify');
            }
            //Votes aus Datenbank löschen
            Vote::where('track_id',$request->id)->delete();
            //Track aus Datenbank löschen
            Track::where('id',$request->id)->delete();
            Alert::info('Abstimmung','Die Abstimmung ist beendet. Der Track hat es nicht in die Playlist geschafft');
            return redirect('/'); 

        }
        Alert::info('Abstimmung','Die Abstimmung kann noch nicht beendet werden. Es fehlen noch Stimmen.');
        return redirect('/');  
    }
}
