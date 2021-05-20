<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{

    public function testRoot()
    {
        $this->get('/');

        $this->assertStringContainsString(
            'https://github.com/arai-ta/cwmh',
            $this->response->getContent()
        );
    }

    public function testStart()
    {
        $this->get('/start');

        $this->response->assertSessionHas('state');

        $this->assertEquals(302, $this->response->getStatusCode());

        $this->assertStringContainsString(
            'https://www.chatwork.com/packages/oauth2/login.php',
            $this->response->headers->get('Location')
        );
    }

}
