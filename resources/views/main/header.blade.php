<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <link href="/css/app.css" rel="stylesheet">
    <script type="text/javascript" src="/js/app.js"></script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title }}</title>
    @if (isset($styleUrls))
        @foreach ($styleUrls as $url)
            <link href="{{ $url }}" rel="stylesheet">
        @endforeach
    @endif
    @if (isset($scripts))
        @foreach ($scripts as $url)
            <script type="text/javascript" src="{{ $url }}"></script>
        @endforeach
    @endif
</head>

<body>