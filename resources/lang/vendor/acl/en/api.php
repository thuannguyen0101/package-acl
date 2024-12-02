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

    'fields' => [
        'string'   => 'string',
        'email'    => 'email',
        'numeric'  => 'number',
        'checkbox' => 'one of the given options.',

        'name'      => 'owner\'s name',
        'username'  => 'user\'s username',
        'avatar'    => 'image URL',
        'password'  => 'password',
        'phone'     => 'phone number',
        'status'    => 'status',
        'filters'   => 'filters',
        'filters.*' => 'filter value',
        'role_id'   => 'role',
        'model_id'  => 'user',
        'tenant_id' => 'owner'
    ],

    // Success messages
    'success'                => 'Operation successful.',
    'created'                => 'Created successfully.',
    'updated'                => 'Updated successfully.',
    'deleted'                => 'Deleted successfully.',

    // Common errors
    'error'                  => 'An error occurred, please try again later.',
    'not_found'              => 'Requested resource not found.',
    'unauthorized'           => 'You are not authorized to access this resource. Please authenticate before proceeding.',
    'forbidden'              => 'You are not allowed to perform this action.',
    'server_error'           => 'Server error, please try again later.',

    // Validation errors
    'validation_with'        => 'One or more required relationships are invalid.',
    'validation_data'        => 'The :attribute must be :type.',
    'validation_fields'      => 'One or more required fields are invalid.',
    'validation_failed'      => 'Invalid data.',
    'required'               => 'The :attribute field is required.',
    'email'                  => 'The :attribute field must be a valid email address.',
    'max_length'             => 'The :attribute field may not be greater than :max characters.',
    'min_length'             => 'The :attribute field must be at least :min characters.',
    'unique'                 => 'The :attribute has already been taken.',
    'exists'                 => 'The :attribute does not exist.',
    'array'                  => 'The :attribute must be an array.',
    'password_mismatch'      => 'Password mismatch.',
    'numeric'                => 'The :attribute must be of the specified type.',
    'in'                     => 'The :attribute is invalid. Please select one of the given values.',
    'alpha_num'              => 'The :attribute field may only contain letters and numbers.',

    // User authentication
    'login_failed'           => 'Login failed, please check your credentials.',
    'login_success'          => 'Login successful.',
    'logout_success'         => 'Logout successful.',
    'register_success'       => 'Registration successful.',
    'password_reset_success' => 'Password has been reset successfully.',
    'password_reset_failed'  => 'Password reset failed.',
    'token_expired'          => 'Authentication token has expired.',
    'token_invalid'          => 'Authentication token is invalid.',
    'account_disabled'       => 'Your account has been disabled.',
    'account_not_owned'      => 'This account does not belong to you.',

    // Authorization
    'no_permission'          => 'You do not have permission to perform this action.',

    // Data operations
    'data_saved'             => 'Data has been saved successfully.',
    'data_updated'           => 'Data has been updated successfully.',
    'data_deleted'           => 'Data has been deleted successfully.',
    'data_not_found'         => 'Data not found.',

    // Miscellaneous errors
    'too_many_requests'      => 'You have made too many requests. Please try again in :seconds seconds.',
    'action_not_allowed'     => 'Action not allowed.',
    'invalid_credentials'    => 'Invalid credentials.',
    'csrf_token_mismatch'    => 'Invalid request due to CSRF token mismatch.',

    'conflict' => [
        'account_exists' => 'The account already exists.',
        'email_taken'         => 'The email is already used for an account. Please use another email or log in.',
        'action_conflict'     => 'This action cannot be completed due to conflicts with current data.',
        'pending_action'      => 'You have a pending action. Please cancel the previous action to continue.',
        'subscription_exists' => 'You already have an active subscription. Please cancel the current plan before subscribing to a new one.',
        'duplicate_request'   => 'The request has already been made. Please wait for processing or try again later.',
        'already_subscribed'  => 'You are already subscribed to this service. Action cannot be repeated.',
    ],

    'no_data'        => 'No data found.',
    'data_not_owned' => 'This data does not belong to you.',

    // Permission
    'permission'     => [
        'not_found'         => 'Permission not found.',
        'message_not_found' => 'No data found.',
        'message_updated'   => 'Data has been updated successfully.'
    ],

    'user' => [
        'not_found' => 'User not found.'
    ],

    'role' => [
        'not_found' => 'Role not found.'
    ]
];
