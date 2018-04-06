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
    '/objects/create/{connectionId}' => [
        'POST' => [
            'action' => 'Objects/Create'
        ]
    ],
    '/objects/delete/{connectionId}' => [
        'POST' => [
            'action' => 'Objects/Delete'
        ]
    ],
    '/objects/batch_delete/{connectionId}' => [
        'POST' => [
            'action' => 'Objects/BatchDelete'
        ]
    ],
    '/objects/invalidate/{connectionId}' => [
        'POST' => [
            'action' => 'Objects/Invalidation'
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
    ],
    '/folder/{connectionId}' => [
        'POST' => [
            'action' => 'Folder/Create'
        ]
    ]
];