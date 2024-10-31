<?php
return [
    'tables' => [
        'auth_tokens_table' => env('AUTH_TOKENS_TABLE', 'users'),
    ],

    'roles' => [
        'admin',
        'user'
    ],

    'permissions' => [
        'account' => [
            'index_account',
            'create_account',
            'view_account',
            'edit_account',
            'delete_account'
        ]
    ]

];
