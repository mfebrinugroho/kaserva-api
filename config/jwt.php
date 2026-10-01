<?php

return [
    'access' => [
        'secret' => env('JWT_ACCESS_SECRET'),
        'ttl' => (int) env('JWT_ACCESS_TTL', 900),
        'algorithm' => 'HS256',
    ],

    'refresh' => [
        'secret' => env('JWT_REFRESH_SECRET'),
        'ttl' => (int) env('JWT_REFRESH_TTL', 604800),
        'algorithm' => 'HS256',
    ],
];
