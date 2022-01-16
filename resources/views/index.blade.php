@extends('layouts.masterLayout')

@section('content')
    <div class="container">

        <div class="btn-group" role="group" aria-label="Basic example">
            <a href="/addTrack" class="btn btn-outline-primary">Neuen Track hinzufügen</a>
        </div>
        @if ($Tracks->count() > 0)
            <!-- Tabelle anlegen-->
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Upvotes</th>
                        <th scope="col">Downvotes</th>
                        <th scope="col">Abstimmen</th>
                        <th scope="col">Check Results</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($Tracks as $Track)
                        <tr>
                            <td>{{ $Track->name }}</td>
                            <td>{{ $Track->voteCommit }}</td>
                            <td>{{ $Track->voteDiscard }}</td>
                            <td>
                                <a class="btn btn-outline-primary" href="/upvote/{{ $Track->id }}">
                                    <img class="votes" src="\img\upVote.png" alt="Upvote"></a>
                                <a class="btn btn-outline-primary" href="/downvote/{{ $Track->id }}">
                                    <img class="votes" src="\img\downVote.png" alt="Upvote"></a>
                            </td>
                            <td>
                                <a class="btn btn-outline-primary" href="/checkResults/{{ $Track->id }}">Abstimmung
                                    beenden</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <br><br>
            <h4 class="btn btn-outline-secondary">Es wurden noch keine Tracks zur Abstimmung hinzugefügt</h4>

        @endif

    </div>
@endsection
