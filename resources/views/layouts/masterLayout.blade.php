<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vote Title</title>
    <link rel="stylesheet" href={{ asset('css/app.css') }}>
    <link rel="stylesheet" href={{ asset('css/Style.css') }}>

</head>

<body>
    @include('sweetalert::alert')
    <!-- Navigationsleiste -->
    <div class="container">
        <div class="navbar">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <span class="navbarElement">
                    <a class="btn btn-primary" href="/">Startseite</a>
                </span>

                @if (Auth()->User())
                    <span class="navbarElement">
                        <a href="/logout" class="btn btn-primary">Abmelden</a>
                    </span>
                @else
                    <span class="navbarElement">
                        <a href="/login" class="btn btn-primary">Login</a>
                    </span>
                    <span class="navbarElement">
                        <a id="register" href="/register" class="btn btn-primary">Registrieren</a>
                    </span>


                @endif

            </nav>
        </div>
    </div>

    <br><b><br>
        @yield('content')
</body>
