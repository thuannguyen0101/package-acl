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
        'array'           => 'The :attribute must be an array.',
        'integer'         => 'The :attribute must be a number.',
    ],

    'fields'       => [
        'string'   => 'string',
        'email'    => 'email',
        'numeric'  => 'number',
        'checkbox' => 'one of the given options.',

        'name'      => 'Name',
        'root_id'   => 'Root ID',
        'parent_id' => 'Parent ID',
        'url'       => 'URL',
        'type'      => 'Type',
        'icon'      => 'Icon',
        'view_data' => 'View Data',
        'label'     => 'Label',
        'layout'    => 'Layout',
        'sort'      => 'Sort',
        'is_auth'   => 'Is Auth',
        'status'    => 'Status',
        'meta'      => 'Meta',
    ],

    // Success messages
    'success'      => 'Operation successful.',
    'created'      => 'Created successfully.',
    'updated'      => 'Updated successfully.',
    'deleted'      => 'Deleted successfully.',

    // Common errors
    'error'        => 'An error occurred, please try again later.',
    'not_found'    => 'Requested resource not found.',
    'unauthorized' => 'You are not authorized to access this resource. Please authenticate before proceeding.',
    'forbidden'    => 'You are not allowed to perform this action.',
    'server_error' => 'Server error, please try again later.',

    'no_data'        => 'No data found.',
    'data_not_owned' => 'This data does not belong to you.',
];
