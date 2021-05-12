<?php

$default = require __DIR__.'/../vendor/laravel/lumen-framework/config/database.php';

return array_merge_recursive([
    // @see https://github.com/laravel/laravel/blob/master/config/database.php
    'connections' => [
        'heroku' => [
            'driver' => 'mysql',
            'url' => env('CLEARDB_DATABASE_URL'),
            'database' => env('DB_DATABASE', 'forge'),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
        ]
    ]
], $default);
