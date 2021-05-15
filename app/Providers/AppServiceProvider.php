<?php

namespace App\Providers;

use ChatWork\OAuth2\Client\ChatWorkProvider;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use Psr\Log\LogLevel;

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
            $stack = HandlerStack::create();

            $stack->push(
                Middleware::log(
                    Log::channel(),
                    new MessageFormatter('{uri} : {req_body} ({req_headers}) --> {res_body}'),
                    LogLevel::DEBUG
                )
            );

            return new ChatWorkProvider(
                env('OAUTH_CLIENT_ID'),
                env('OAUTH_CLIENT_SECRET'),
                url('/callback', [], true),
                [
                    'httpClient' => new Client(['handler' => $stack])
                ]
            );
        });
    }
}
