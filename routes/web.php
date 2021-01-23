<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->get('/start', function () {
    $provider = new ChatWork\OAuth2\Client\ChatWorkProvider(
        env('OAUTH_CLIENT_ID'),
        env('OAUTH_CLIENT_SECRET'),
        url('/callback')
    );

    $url = $provider->getAuthorizationUrl([
        'scope' => ['offline_access', 'users.profile.me:read']
    ]);

    $state = $provider->getState();

    dump($url, $state);

    return "redirect to Authorization Endpoint!";
});
