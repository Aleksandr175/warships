<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Warships</title>
        <script src="{{ asset('js/app.js') }}" defer></script>
    </head>
    <body>
        Main page
        <a href="{{ route('login') }}">Login</a>
    </body>
</html>
