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

    'fields'       => [
        'string'   => 'string',
        'email'    => 'email',
        'numeric'  => 'number',
        'checkbox' => 'one of the given options.',

        'name' => [
            'account_money'     => 'fund name',
            'expense_category ' => 'category name',
            'budget'            => 'budget name',
        ],

        'description' => [
            'account_money'     => 'fund description',
            'expense_category ' => 'category description',
            'budget'            => 'budget description',
        ],

        'status'              => 'Status',
        'expense_category_id' => 'Expense category id',
        'account_money_id'    => 'Account money id',
        'meta_content'        => 'Meta content',
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
