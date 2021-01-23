<html>
    <head>
        <title>
@isset($title) {{ $title . ' - ' }} @endisset Chatwork 通知履歴
        </title>
    </head>
    <body>
        <h1>{{ $header ?? 'This is header' }}</h1>
        <hr/>
        {{ $slot }}
    </body>
</html>
