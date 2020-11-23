<?php

return [
    'rules' => [
        'fish-create' => [
            'validations' => [
                'name' => ['required','string','min:4'],
                'age' => ['required','string','min:4'],
                'breed' => ['required','string','min:4']
            ],
            'normalizations' => [
                'name' => ['trim'],
                'breed' => ['strtoupper'],
            ],
            'decrypt' => [
                'key' => '1k',
                'fields' => [
                    'breed'
                ]
            ]
        ],
        'test-test' => 'fish-create',
    ],
];