<?php

return [
    '/objects/list/{connectionId}' => [
        'POST' => [
            'action' => 'Objects/Listing',
        ]
    ],
    '/objects/retrieve/{connectionId}' => [
        'POST' => [
            'action' => 'Objects/Retrieve'
        ]
    ],
    '/buckets/{connectionId}' => [
        'GET' => [
            'action' => 'Buckets/Retrieve'
        ]
    ],
    '/authentications' => [
        'GET' => [
            'action' => 'Authentications/Retrieve',
        ],
        'POST' => [
            'action' => 'Authentications/Register',
        ]
    ]
];