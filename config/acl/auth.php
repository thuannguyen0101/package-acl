<?php
return [
    'guards' => [
        'jwt-role-permission' => [
            'driver' => 'jwt',
            'provider' => 'users_api',
            'hash' => false,
        ],
    ],

    'providers' => [
        'users_api' => [
            'driver' => 'eloquent',
            'model' => \Workable\ACL\Models\UserApi::class,
        ],
    ],
];
