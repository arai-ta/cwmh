<?php

namespace App\Http\Controllers;

use ChatWork\OAuth2\Client\ChatWorkProvider;
use Illuminate\Http\Request;

class StartController extends Controller
{

    public function __invoke(Request $request, ChatWorkProvider $provider)
    {
        $url = $provider->getAuthorizationUrl([
            'scope' => [
                'users.profile.me:read',
                'rooms:write',
                'rooms.messages:write',
                'offline_access',
            ]
        ]);

        $request->session()->put('state', $provider->getState());

        return redirect($url);
    }
}
