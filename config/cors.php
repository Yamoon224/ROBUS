<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

     'paths' => [
        'api/*',
        'broadcasting/auth',
    ],

    'allowed_methods' => ['*'],

    'allowed_origins' => ['*'], // Pour test, puis restreignez

    'allowed_headers' => ['*'],

    'allowed_methods' => ['POST', 'GET', 'OPTIONS'],

    // 'allowed_origins' => [
    //     'https://robus.home.ci',
    //     'https://manager.robus.africa',
    // ],

    'allowed_origins_patterns' => [],

    // 'allowed_headers' => [
    //     'Authorization',
    //     'Content-Type',
    //     'Accept',
    //     'X-Requested-With',
    //     'X-Socket-ID',
    // ],

    'exposed_headers' => [],

    'max_age' => 86400,

    'supports_credentials' => true,
];