<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="_token" content="{{ Session::token() }}" />
        @yield("meta")

        <title>Receipt Builder</title>

        @include("templates.css")
        @yield("css")
    </head>
    <body>
        <div class="container-fluid">
            @yield("content")
        </div>
        <br><br><br>
        @include("templates.html")
        @include("templates.js")
        @yield("js")
    </body>
</html>