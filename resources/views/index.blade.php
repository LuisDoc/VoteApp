@extends('layouts.masterLayout')

@section('content')
    <div class="container">

        <div class="btn-group" role="group" aria-label="Basic example">
            <a href="/addTrack" class="btn btn-primary">Neuen Track hinzufügen</a>
        </div>

        <!-- Tabelle anlegen-->
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Upvotes</th>
                    <th scope="col">Downvotes</th>
                    <th scope="col">Vote yourself</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($Tracks as $Track)
                    <tr>
                        <td>{{ $Track->id }}</td>
                        <td>{{ $Track->name }}</td>
                        <td>{{ $Track->voteCommit }}</td>
                        <td>{{ $Track->voteDiscard }}</td>
                        <td>
                            <a href="/upvote/{{ $Track->id }}">upvote</a>
                            <a href="/downvote/{{ $Track->id }}">downvote</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
