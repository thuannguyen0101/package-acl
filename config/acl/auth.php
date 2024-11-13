<?php
return [
    'guards' => [
        'acl' => [
            'driver'   => 'session',
            'provider' => 'acl_users',
        ],
    ],

    'providers' => [
        'acl_users' => [
            'driver' => 'eloquent',
            'model'  => Workable\ACL\Models\User::class,
        ],
    ],

    'middleware' => [
        'jwt_auth_check'
    ]
];
