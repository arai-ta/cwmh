<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Log;
use League\OAuth2\Client\Grant\AuthorizationCode;
use Monolog\Handler\StreamHandler;
use Monolog\Logger as MonologLogger;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () {
    return view('home');
});

$router->get('/start', function (\Illuminate\Http\Request $request) {
    $provider = new ChatWork\OAuth2\Client\ChatWorkProvider(
        env('OAUTH_CLIENT_ID'),
        env('OAUTH_CLIENT_SECRET'),
        url('/callback', [], true)
    );

    $url = $provider->getAuthorizationUrl([
        'scope' => ['offline_access', 'users.profile.me:read']
    ]);

    $state = $provider->getState();

    Log::info("state = {$state}, url = {$url}");

    $request->session()->put('state', $state);

    return redirect($url);
});

$router->get('/callback', function (\Illuminate\Http\Request $request) {
    $state = $request->session()->get('state');

    if ($state !== $request->input('state')) {
        Log::error("invalid state error. session = {$state}, request = {$request->input('state')}");
        return "invalid state error";
    }

    $logger = new MonologLogger('http_logging');
    $logger->pushHandler(new StreamHandler('../storage/logs/http.log', MonologLogger::DEBUG));
    $stack = HandlerStack::create();
    $stack->push(
        Middleware::log(
            $logger,
            new MessageFormatter('{uri} : {req_body} -> {res_body}')
        )
    );
    $client = new GuzzleHttp\Client(['handler' => $stack]);

    $provider = new ChatWork\OAuth2\Client\ChatWorkProvider(
        env('OAUTH_CLIENT_ID'),
        env('OAUTH_CLIENT_SECRET'),
        url('/callback', [], true),
        [
            'httpClient' => $client
        ]
    );

    $token = $provider->getAccessToken(new AuthorizationCode(), ['code' => $request->input('code')]);

    dump($token);

    return "OK";
});
