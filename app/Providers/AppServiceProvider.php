<?php

namespace App\Providers;

use ChatWork\OAuth2\Client\ChatWorkProvider;
use Illuminate\Support\ServiceProvider;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use Monolog\Handler\StreamHandler;
use Monolog\Logger as MonologLogger;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ChatWorkProvider::class, function() {
            $logger = new MonologLogger('http_logging');
            $logger->pushHandler(new StreamHandler('../storage/logs/http.log', MonologLogger::DEBUG));

            $stack = HandlerStack::create();
            $stack->push(
                Middleware::log(
                    $logger,
                    new MessageFormatter('{uri} : {req_body} ({req_headers}) --> {res_body}')
                )
            );
            $client = new Client(['handler' => $stack]);

            return new ChatWorkProvider(
                env('OAUTH_CLIENT_ID'),
                env('OAUTH_CLIENT_SECRET'),
                url('/callback', [], true),
                [
                    'httpClient' => $client
                ]
            );
        });
    }
}
