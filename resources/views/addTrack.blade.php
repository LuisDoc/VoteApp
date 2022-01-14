@extends('layouts.masterLayout')
@section('content')
    <div class="container">
        <h3>Neuen Track hinzufügen</h3>
        <form method="post" action="/createTrack">
            @csrf
            <div class="form-group">
                <label for="trackname">Songnamen</label>
                <input class="form-control" name="trackname" id="trackname">
            </div>
            <br>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
@endsection
