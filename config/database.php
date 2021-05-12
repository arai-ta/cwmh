<?php

$default = require __DIR__.'/../vendor/laravel/lumen-framework/config/database.php';

return array_merge_recursive([
    // @see https://github.com/laravel/laravel/blob/master/config/database.php
    'connections' => [
        'heroku' => [
            'driver' => 'mysql',
            'url' => env('CLEARDB_DATABASE_URL'),
            'database' => env('DB_DATABASE', 'forge'),
            'charset' => env('DB_CHARSET', 'utf8mb4'),
            'collation' => env('DB_COLLATION', 'utf8mb4_unicode_ci'),
            'strict' => env('DB_STRICT_MODE', true),
            'timezone' => env('DB_TIMEZONE', '+00:00'),
        ]
    ]
], $default);
