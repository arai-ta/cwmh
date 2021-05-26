<html>
    <head>
        <title>
@isset($title) {{ $title . ' - ' }} @endisset
{{ config('app.name') }}
        </title>
    </head>
    <body>
        <h1>{{ $header ?? 'This is header' }}</h1>
        <hr/>
        {{ $slot }}
    </body>
</html>
