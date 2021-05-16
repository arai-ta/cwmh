<?php

namespace App\Http\Controllers;

use App\Models\User;
use ChatWork\OAuth2\Client\ChatWorkProvider;
use Laravel\Lumen\Http\Request;
use League\OAuth2\Client\Grant\AuthorizationCode;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

class CallbackController extends Controller
{
    public function __invoke(Request $request, ChatWorkProvider $provider)
    {
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
        } catch (IdentityProviderException $e) {
            return view("error", [
                'message' => "service linkage error:".$e->getMessage()
            ]);
        }

        $id = $provider->getResourceOwner($token)->getId();

        // log in
        $request->session()->put('account_id', $id);

        /** @var User $user */
        $user = User::firstOrCreate(['account_id' => $id]);
        $user->updateToken($token);

        return redirect('config');
    }
}
