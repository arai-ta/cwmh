<html>
    <head>
        <title>{{ $title ?? 'Chatwork Mention History' }}</title>
    </head>
    <body>
        <h1>{{ $header ?? 'This is header' }}</h1>
        <hr/>
        {{ $slot }}
    </body>
</html>
