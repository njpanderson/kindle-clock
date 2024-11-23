<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Kindle</title>

        @vite(['resources/css/app.css'])
    </head>

    <body class="font-sans antialiased">
        <iframe src="/ui" class="absolute left-0 top-0 right-0 w-full h-full"></iframe>
    </body>
</html>
