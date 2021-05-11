<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use ChatWork\OAuth2\Client\ChatWorkProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use League\OAuth2\Client\Grant\AuthorizationCode;
use League\OAuth2\Client\Grant\RefreshToken;

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

$router->get('/start', function (Request $request, ChatWorkProvider $provider) {

    $url = $provider->getAuthorizationUrl([
        'scope' => [
            'users.profile.me:read',
            'rooms:write',
            'rooms.messages:write',
            'offline_access',
        ]
    ]);

    $state = $provider->getState();

    Log::info("state = {$state}, url = {$url}");

    $request->session()->put('state', $state);

    return redirect($url);
});

$router->get('/callback', function (Request $request, ChatWorkProvider $provider) {
    $state = $request->session()->get('state');

    if ($state !== $request->input('state')) {
        Log::error("invalid state error. session = {$state}, request = {$request->input('state')}");
        return view("error", [
            'message' => "invalid state error"
        ]);
    }

    if ($request->input('error', false)) {
        return view("error", [
            'message' => "service linkage error:".$request->input('error')
        ]);
    }

    try {
        $token = $provider->getAccessToken(new AuthorizationCode(), ['code' => $request->input('code')]);
    } catch (League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
        return view("error", [
            'message' => "service linkage error:".$e->getMessage()
        ]);
    }

    $owner = $provider->getResourceOwner($token);

    $accountId = $owner->getId();
    $request->session()->put('account_id', $accountId);

    $user = \App\Models\User::query()
        ->where('account_id', $accountId)
        ->first();

    if ($user) {
        $user->token = $token->jsonSerialize();
        $user->save();
    } else {
        $user = \App\Models\User::save([
            'account_id' => $accountId,
            'token' => $token->jsonSerialize(),
        ]);
    }

    return redirect('./config');
});

$router->get('/config', function (Request $request, ChatWorkProvider $provider) {

    $accountId = $request->session()->get('account_id');

    /** @var \App\Models\User $user */
    $user = \App\Models\User::query()->where('account_id', $accountId)->first();

    if (!$user) {
        $request->session()->forget('account_id');
        return redirect('/');
    }

    $token = $user->getToken();
    if ($token->hasExpired()) {
        $token = $provider->getAccessToken(new RefreshToken(), ['refresh_token' => $token->getRefreshToken()]);
        $user->updateToken($token);
    }

    return view('config', [
        'hook' => $user->hook
    ]);
});


$router->post('/config', function (Request $request, ChatWorkProvider $provider) {

    $accountId = $request->session()->get('account_id');

    /** @var \App\Models\User $user */
    $user = \App\Models\User::query()->where('account_id', $accountId)->first();

    if (!$user) {
        $request->session()->forget('account_id');
        return redirect('/');
    }

    $token = $user->getToken();
    if ($token->hasExpired()) {
        $token = $provider->getAccessToken(new RefreshToken(), ['refresh_token' => $token->getRefreshToken()]);
        $user->updateToken($token);
    }

    $client = \SunAsterisk\Chatwork\Chatwork::withAccessToken($token->getToken());
    $result = $client->rooms()->create([
        'name' => $request->input('roomname'),
        'members_admin_ids' => [$user->account_id],
        'description' => 'created by '.env('APP_NAME').'. at [date:'.time().']',
        'link'  => 0,
        'icon_preset' => 'check',
    ]);

    $hook = new \App\Models\Hook();
    $hook->target_room_id = $result['room_id'];
    $hook->key = strtr(base64_encode(random_bytes(24)), ['+' => '-', '/' => '_']);

    $user->hook()->save($hook);

    return redirect('./config');
});


$router->get('/hook/{key}', function ($key, Request $request, ChatWorkProvider $provider) {
    $hook = \App\Models\Hook::query()->where(['key' => $key])->first();
    if (is_null($hook)) {
        return 404;
    }

    $user = $hook->user;

    $token = $user->getToken();
    if ($token->hasExpired()) {
        $token = $provider->getAccessToken(new RefreshToken(), ['refresh_token' => $token->getRefreshToken()]);
        $user->updateToken($token);
    }

    // 署名検証、失敗したら403

    // メッセージの解析、投稿メッセージの組み立て

    // 通知部屋にPOST

    return "OK";
});
