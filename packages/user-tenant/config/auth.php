<?php
return [
    'guards' => [
        'api' => [
            'driver'   => 'jwt',
            'provider' => 'users',
            'hash'     => false,
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model'  => \Workable\UserTenant\Models\User::class,
        ],
    ],

    'middleware' => [
        'jwt_auth_check'
    ]
];
