<?php
return [
    'field_validates' => [
        'required'        => 'The :attribute field is required.',
        'string'          => 'The :attribute field must be a string.',
        'email'           => 'The :attribute field must be a valid email address.',
        'max'             => 'The :attribute field may not be greater than :max characters.',
        'min'             => 'The :attribute field must be at least :min characters.',
        'unique'          => 'The :attribute field has already been taken.',
        'validation_data' => 'The :attribute data must be of type :type.',
        'in'              => 'The :attribute data must be one of the provided options.',
        'date'            => 'The :attribute field must be a valid date.',
        'numeric'         => 'The :attribute field must be a number.',
        'confirmed'       => 'The confirmation field must match the password field.',
        'alpha_num'       => 'The :attribute field may only contain letters and numbers (no special characters).',
        'array'           => 'The :attribute field must be an array.',
    ],

    'fields'                 => [
        'string'   => 'string',
        'email'    => 'email',
        'numeric'  => 'number',
        'checkbox' => 'one of the provided options.',

        'name'      => 'owner\'s name',
        'username'  => 'user\'s login name',
        'avatar'    => 'image path',
        'password'  => 'password',
        'phone'     => 'phone number',
        'status'    => 'status',
        'filters'   => 'filters',
        'filters.*' => 'filter value',
        'role_id'   => 'role',
        'model_id'  => 'user',
        'tenant_id' => 'owner',
    ],

    // Success messages
    'success'                => 'Operation successful.',
    'created'                => 'Successfully created.',
    'updated'                => 'Successfully updated.',
    'deleted'                => 'Successfully deleted.',

    // Common errors
    'error'                  => 'An error occurred, please try again later.',
    'not_found'              => 'Requested resource not found.',
    'unauthorized'           => 'You do not have permission to access this resource. Please authenticate before proceeding.',
    'forbidden'              => 'You are not allowed to perform this action.',
    'server_error'           => 'Server error, please try again later.',
];

