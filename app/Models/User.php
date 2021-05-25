<?php

namespace App\Models;

use App\Chatwork\ServiceUrl;
use Illuminate\Database\Eloquent\Model;
use League\OAuth2\Client\Token\AccessToken;
use Illuminate\Contracts\Encryption\DecryptException;

/**
 * @property Hook $hook
 * @property int $account_id
 *
 * @property string token
 */
class User extends Model
{
    protected $fillable = [
        'account_id',
        'token',
    ];

    public function getToken(): AccessToken
    {
        try {
            return new AccessToken(json_decode(decrypt($this->token), true));
        } catch (DecryptException $e) {
            return new AccessToken(json_decode($this->token, true));
        }
    }

    public function updateToken(AccessToken $token)
    {
        $this->token = encrypt(json_encode($token));
        $this->save();
    }

    public function getServiceUrl(): ServiceUrl
    {
        return ServiceUrl::create(); // 後でKCWと切り替えるフラグを渡す
    }

    public function hook()
    {
        return $this->hasOne(Hook::class);
    }
}
