<?php
return [
    'success'        => 'Operation successful.',
    'created'        => 'Created successfully.',
    'updated'        => 'Updated successfully.',
    'deleted'        => 'Deleted successfully.',
    'no_data'        => 'No data found.',
    'data_not_found' => 'Data not found.',
    'not_found'      => 'Requested resource not found.',
    'data_not_owned' => 'This data does not belong to you.',
    'tenant'         => 'This data does not belong to you.',

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
        'old_password'    => 'The old password is incorrect.',
        'validation_fields'      => 'One or more required fields are invalid.',
    ],

    'fields' => [
        'string'         => 'string',
        'email'          => 'email',
        'numeric'        => 'number',
        'checkbox'       => 'one of the given options.',
        'name'           => 'name of the owner',
        'username'       => 'user\'s username',
        'avatar'         => 'image URL',
        'password'       => 'password',
        'phone'          => 'phone number',
        'status'         => 'status',
        'address'        => 'address',
        'gender'         => 'gender',
        'birthday'       => 'birthday',
        'size'           => 'usage scale',
        'citizen_id'     => 'citizen ID',
        'start_at'       => 'start date',
        'expiry_at'      => 'end date',
        'full_name'      => 'full name',
        'description'    => 'description',
        'business_phone' => 'business phone number',
        'meta_attribute' => 'meta attribute',
        "established"    => 'year of establishment',
        "work_day"       => 'workday',
        "uniform"        => 'uniform',
        "skype"          => 'skype',
        "position"       => 'position',
        "old_password"   => 'old password',
        "login"          => 'user\'s login username'
    ],

    'status_text' => [
        'active'   => 'Active',
        'inactive' => 'Inactive',
        'blocked'  => 'Blocked'
    ],

    'gender_text' => [
        'male'   => 'Male',
        'female' => 'Female',
        'other'  => 'Other',
    ],

    'size_text' => [
        "under_10"   => 'Under 10',
        "10_25"      => '10 - 25',
        "25_50"      => '25 - 50',
        "50_100"     => '50 - 100',
        "100_200"    => '100 - 200',
        "200_500"    => '200 - 500',
        "500_1000"   => '500 - 1000',
        "above_1000" => 'Over 1000',
    ],

    'work_day_text' => [
        "monday_friday"         => "Monday - Friday",
        "monday_friday_morning" => "Monday - Friday Morning",
        "monday-saturday"       => "Monday - Saturday",
        "full_week"             => "Full Week",
        "flexible"              => "Flexible",
        "other"                 => "Other",
    ],

    'level_text' => [
        "staff"           => 'Staff',
        "team_leader"     => 'Team Leader',
        "deputy_manager"  => "Deputy Manager",
        "manager"         => "Manager",
        "deputy_director" => "Deputy Director",
        "director"        => "Director",
        "ceo"             => "CEO",
        "other"           => "Other",
    ],

    'auth' => [
        'unauthorized'  => 'You are not authorized to access this resource. Please authenticate before proceeding.',
        'forbidden'     => 'You are not allowed to perform this action.',
        'token_expired' => 'Authentication token has expired.',
        'server_error'  => 'Server error, please try again later.',
        'token_invalid' => 'Authentication token is invalid.',
        'login_failed'  => 'Login failed, please check your credentials.',
        'login_success' => 'Login successful.',
    ],

    'tenants' => [
        'conflict' => 'A representative already exists.'
    ]
];
