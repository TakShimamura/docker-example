<?php

return [
    'default' => [
        'mode' => 'pkcs1',
        'key' => '1k',
        'jwt-key' => '4k',
        'jwt-mode' => 'rs',
    ],
    'keys' => [
        '1k' => [
            'public' => env('RSA_1K_PUBLIC'),
            'secret' => env('RSA_1K_SECRET'),
        ],
        '4k' => [
            'public' => env('RSA_4K_PUBLIC'),
            'secret' => env('RSA_4K_SECRET'),
        ]

    ],
];
