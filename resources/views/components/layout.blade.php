<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Subscriber manager</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        <style>
        </style>
    </head>
    <body>
    <div class="container">
    <h1>Subscriber manager</h1>
        {{ $slot }}
    </div>
    </body>
</html>
