@extends('layouts.masterLayout')
@section('content')
    <div class="container">
        <h3>Neuen Track hinzufügen</h3>
        <form method="post" action="/searchTrack">
            @csrf
            <div class="form-group">
                <label for="trackname">Songnamen</label>
                <input class="form-control" name="trackname" id="trackname" placeholder="{{ $suchtext }}" required>
            </div>
            <br>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
        <br><br><br>
        @if ($searchResults)
            <h3>Gefundene Titel</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Artist</th>
                        <th scope="col">Hinzufügen</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($searchResults['tracks']['items'] as $track)
                        <tr>
                            <td>{{ $track['name'] }}</td>
                            <td>
                                @foreach ($track['artists'] as $artist)
                                    {{ $artist['name'] . ' ' }}
                                @endforeach
                            </td>
                            <td>
                                <a href="/createTrack/{{ $track['id'] }}" class="btn btn-primary">Hinzufügen</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
