<?php
return [
    'field_validates' => [
        'required'        => 'The :attribute field is required.',
        'string'          => 'The :attribute field must be a string.',
        'email'           => 'The :attribute field must be a valid email address.',
        'max'             => 'The :attribute field may not be greater than :max characters.',
        'min'             => 'The :attribute field must be at least :min characters.',
        'unique'          => 'The :attribute has already been taken.',
        'validation_data' => 'The :attribute must be :type.',
        'in'              => 'The :attribute must be one of the given options.',
        'date'            => 'The :attribute must be a valid date.',
        'numeric'         => 'The :attribute must be a number.',
        'confirmed'       => 'The confirmation field must match the password.',
        'alpha_num'       => 'The :attribute field may only contain letters and numbers.',
        'array'           => 'The :attribute must be an array.'
    ],

    'fields'            => [
        'string'   => 'string',
        'email'    => 'email',
        'numeric'  => 'number',
        'checkbox' => 'one of the given options.',

        'account_type' => 'account type',
        'bank_name'    => 'bank name',
        'branch_name'  => 'branch name',
    ],

    // Success messages
    'success'           => 'Operation successful.',
    'created'           => 'Created successfully.',
    'updated'           => 'Updated successfully.',
    'deleted'           => 'Deleted successfully.',

    // Common errors
    'error'             => 'An error occurred, please try again later.',
    'not_found'         => 'Requested resource not found.',
    'unauthorized'      => 'You are not authorized to access this resource. Please authenticate before proceeding.',
    'forbidden'         => 'You are not allowed to perform this action.',
    'server_error'      => 'Server error, please try again later.',

    // Validation errors
    'validation_with'   => 'One or more required relationships are invalid.',
    'validation_data'   => 'The :attribute must be :type.',
    'validation_fields' => 'One or more required fields are invalid.',
    'validation_failed' => 'Invalid data.',
    'required'          => 'The :attribute field is required.',
    'email'             => 'The :attribute field must be a valid email address.',
    'max_length'        => 'The :attribute field may not be greater than :max characters.',
    'min_length'        => 'The :attribute field must be at least :min characters.',
    'unique'            => 'The :attribute has already been taken.',
    'exists'            => 'The :attribute does not exist.',
    'array'             => 'The :attribute must be an array.',
    'password_mismatch' => 'Password mismatch.',
    'numeric'           => 'The :attribute must be of the specified type.',
    'in'                => 'The :attribute is invalid. Please select one of the given values.',
    'alpha_num'         => 'The :attribute field may only contain letters and numbers.',

    'conflict' => [
        'account_exists'      => 'The account already exists.',
        'email_taken'         => 'The email is already used for an account. Please use another email or log in.',
        'action_conflict'     => 'This action cannot be completed due to conflicts with current data.',
        'pending_action'      => 'You have a pending action. Please cancel the previous action to continue.',
        'subscription_exists' => 'You already have an active subscription. Please cancel the current plan before subscribing to a new one.',
        'duplicate_request'   => 'The request has already been made. Please wait for processing or try again later.',
        'already_subscribed'  => 'You are already subscribed to this service. Action cannot be repeated.',
    ],

    'no_data'        => 'No data found.',
    'data_not_owned' => 'This data does not belong to you.',
];
