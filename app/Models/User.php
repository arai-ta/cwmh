<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use League\OAuth2\Client\Token\AccessToken;

/**
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
        return new AccessToken(json_decode($this->token, true));
    }

    public function updateToken(AccessToken $token)
    {
        $this->token = json_encode($token->jsonSerialize());
        $this->save();
    }

    public function hook()
    {
        return $this->hasOne(Hook::class);
    }
}
