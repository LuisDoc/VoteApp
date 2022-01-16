<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vote Title</title>
    <link rel="stylesheet" href={{ asset('css/app.css') }}>
</head>

<body>
    <!-- Navigationsleiste -->

    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="/">Startseite</a>
            @if (Auth()->User())
                <a href="/logout" class="navbar-brand">Abmelden</a>
            @else
                <a href="/login" class="navbar-brand">Login</a>
            @endif

        </nav>
    </div>
    <br><b><br>
        @yield('content')
</body>
