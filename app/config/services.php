<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'stripe' => [
        'live' => [
            'keys' => [
                'public' => 'pub_live_1234',
                'secret' => 'sec_live_1234',
            ],
        ],
        'test' => [
            'keys' => [
                'public' => 'pk_test_51GrqEcLtLF731e1LLDWkbE9jVyHEUNcUo1VZ7S2SRJ1n8IhszNic5dn6uH3WRvEF5zffiIedhIbO9V373T2liH6000CE0X8UIb',
                'secret' => 'sk_test_51GrqEcLtLF731e1LJWM4KesITTH7L3s30xMuBHKfkanahXjhNimOtArBEcKtmCQk6pweleeZBNdC9oR87C3DVK6X00s7VUeK5p',
            ],
        ],
    ],
    'auth' => [
        'header' => 'DL-Authorization',
        'fingerprint' => 'DL-Fingerprint',
        'url' => 'http://127.0.0.1:8081/api',
    ],
];
