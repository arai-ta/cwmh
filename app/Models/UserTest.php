<?php

namespace App\Models;

use Laravel\Lumen\Testing\DatabaseMigrations;
use League\OAuth2\Client\Token\AccessToken;


class UserTest extends \TestCase
{

    use DatabaseMigrations;

    /**
     * @test
     */
    public function tokenは暗号化される()
    {
        $user = new User(['account_id' => 123]);
        $token = new AccessToken(['access_token' => "hoge"]);
        $user->updateToken($token);

        $this->assertNotEquals(json_encode($token), $user->token);
        return $user;
    }

    /**
     * @test
     * @depends tokenは暗号化される
     */
    public function tokenは復号できる(User $user)
    {
        $token = $user->getToken();

        $this->assertInstanceOf(AccessToken::class, $token);

        $this->assertSame('hoge', $token->getToken());
    }
}
