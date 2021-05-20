<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use App\Chatwork\MessageTemplate\NotifyMessage;
use App\Chatwork\MessageTemplate\NotifyRoomDescription;
use App\Chatwork\Webhook\MentionToMeEvent;
use App\Models\Hook;
use App\Models\Kick;
use App\Models\User;
use ChatWork\OAuth2\Client\ChatWorkProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use League\OAuth2\Client\Grant\AuthorizationCode;
use League\OAuth2\Client\Grant\RefreshToken;
use SunAsterisk\Chatwork\Chatwork;
use SunAsterisk\Chatwork\Exceptions\APIException;

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
    Log::error('It works!');
    return view('home')
        ->with('sourceUrl', env('APP_SOURCE_DIST_URL', 'https://github.com/arai-ta/cwmh'));
});

$router->get('/start', function (Request $request, ChatWorkProvider $provider) {

    $url = $provider->getAuthorizationUrl([
        'scope' => [
            'users.profile.me:read',
            'rooms.info:read',
            'rooms:write',
            'rooms.messages:write',
            'offline_access',
        ]
    ]);

    $request->session()->put('state', $provider->getState());

    return new RedirectResponse($url);
});

$router->get('/callback', function (Request $request, ChatWorkProvider $provider) {
    $state = $request->session()->get('state');

    if ($state !== $request->input('state')) {
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

    $id = $provider->getResourceOwner($token)->getId();
    $request->session()->put('account_id', $id);

    /** @var User $user */
    $user = User::firstOrNew(['account_id' => $id]);
    $user->updateToken($token);

    return new RedirectResponse('./config');
});

$router->get('/config', function (Request $request) {

    $accountId = $request->session()->get('account_id');

    /** @var User $user */
    $user = User::query()->where('account_id', $accountId)->first();

    if (!$user) {
        $request->session()->forget('account_id');
        return new RedirectResponse('/');
    }

    $hook = $user->hook;

    return view('config', [
        'hook' => $hook,
        'serviceUrl' => $user->getServiceUrl(),
        'lastKick' => $hook ? $hook->kicks()->latest()->first() : null,
        'totalKicks' => $hook ? $hook->kicks()->count() : 0,
    ]);
});

$router->get('/logout', function (Request $request) {
    $request->session()->forget('account_id');
    return new RedirectResponse('/');
});

$router->post('/setroom', function (Request $request, ChatWorkProvider $provider) {

    $accountId = $request->session()->get('account_id');

    /** @var User $user */
    $user = User::query()->where('account_id', $accountId)->first();

    if (!$user) {
        $request->session()->forget('account_id');
        return new RedirectResponse('/');
    }

    $token = $user->getToken();
    if ($token->hasExpired()) {
        $token = $provider->getAccessToken(new RefreshToken(), ['refresh_token' => $token->getRefreshToken()]);
        $user->updateToken($token);
    }

    $client = Chatwork::withAccessToken($token->getToken());
    $result = $client->rooms()->create([
        'name' => $request->input('roomname'),
        'members_admin_ids' => [$user->account_id],
        'description' => NotifyRoomDescription::create()->render(),
        'link'  => 0,
        'icon_preset' => 'check',
    ]);

    $hook = new Hook();
    $hook->setRoomId($result['room_id']);
    $hook->generateKey();

    $user->hook()->save($hook);

    return new RedirectResponse('/config');
});

$router->post('/setwebhook', function (Request $request) {

    $accountId = $request->session()->get('account_id');

    /** @var User $user */
    $user = User::query()->where('account_id', $accountId)->first();

    if (!$user) {
        $request->session()->forget('account_id');
        return new RedirectResponse('/');
    }

    $hook = $user->hook;
    $hook->token = $request->input('webhooktoken');
    $hook->webhook_id = $request->input('webhookid');
    $hook->save();

    return new RedirectResponse('/config');
});

$router->post('/hook/{key}', function ($key, Request $request, ChatWorkProvider $provider) {

    /** @var Hook $hook */
    $hook = Hook::query()->where(['key' => $key])->first();
    if (is_null($hook)) {
        return response("Not found", 404);
    }

    $user = $hook->user;

    $kick = new Kick();

    $token = $user->getToken();
    if ($token->hasExpired()) {
        $token = $provider->getAccessToken(new RefreshToken(), ['refresh_token' => $token->getRefreshToken()]);
        $user->updateToken($token);
    }

    // 署名検証、失敗したら401
    if (!$hook->isValidRequest($request)) {
        // NG
        $kick->result = 'invalid webhook signature';
        $kick->detail = json_encode([
            'sig' => $request->header('X-ChatWorkWebhookSignature'),
            'content' => $request->getContent(),
        ]);
        $hook->kicks()->save($kick);
        return response("Bad request", 401);
    }

    $event  = MentionToMeEvent::fromJsonString($request->getContent());
    $client = Chatwork::withAccessToken($token->getToken());

    try {
        $roomName = $client->room($event->roomId)->detail()['name'];

        $result = $client->room($hook->target_room_id)->messages()->create(
            new NotifyMessage($event, $user->getServiceUrl(), $roomName)
        );

        $kick->result = "OK";
        $kick->detail = json_encode($result);
    } catch (APIException $e) {
        Log::error($e);
        $kick->result = "API error"; // maybe 4xx
        $kick->detail = (string) $e;
    } catch (Exception $e) {
        Log::error($e);
        $kick->result = "Unknown error"; // perhaps 5xx or timeout
        $kick->detail = (string) $e;
    }

    $hook->kicks()->save($kick);

    return "OK";

});
