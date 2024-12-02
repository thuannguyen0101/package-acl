<?php
return [
    'field_validates' => [
        'required'        => 'Trường :attribute là bắt buộc.',
        'string'          => 'Trường :attribute phải là chuỗi ký tự.',
        'email'           => 'Trường :attribute phải là địa chỉ email hợp lệ.',
        'max'             => 'Trường :attribute không được dài hơn :max ký tự.',
        'min'             => 'Trường :attribute phải chứa ít nhất :min ký tự.',
        'unique'          => 'Trường :attribute đã tồn tại.',
        'validation_data' => 'Dữ liệu :attribute phải là :type.',
        'in'              => 'Dữ liệu :attribute phải là các lựa chọn được đưa ra.',
        'date'            => 'Trường :attribute phải là ngày hợp lệ.',
        'numeric'         => 'Trường :attribute phải là số.',
        'confirmed'       => 'Trường xác nhận phải khớp với trường mật khẩu.',
        'alpha_num'       => 'Trường :attribute chỉ chấp nhận các ký tự chữ và số (không có ký tự đặc biệt).',
        'array'           => 'Trường :attribute phải là một mảng.'
    ],

    'fields'            => [
        'string'      => 'chuỗi',
        'email'       => 'email',
        'numeric'     => 'số',
        'checkbox'    => 'một trong các lựa chọn đã đưa ra. ',

        'name'        => [
            'account_money'     => 'tên khoản quỹ',
            'expense_category ' => 'tên khoảng mục',
            'budget'            => 'tên ngân sách',
        ],
        'description' => [
            'account_money'     => 'mô tả khoản quỹ',
            'expense_category ' => 'mô tả khoảng mục',
            'budget'            => 'mô tả ngân sách',
        ]
    ],

    // Thông báo thành công
    'success'           => 'Thao tác thành công.',
    'created'           => 'Tạo mới thành công.',
    'updated'           => 'Cập nhật thành công.',
    'deleted'           => 'Xóa thành công.',

    // Lỗi chung
    'error'             => 'Đã xảy ra lỗi, vui lòng thử lại sau.',
    'not_found'         => 'Không tìm thấy tài nguyên yêu cầu.',
    'unauthorized'      => 'Bạn không có quyền truy cập vào tài nguyên này. Vui lòng xác thực trước khi tiếp tục.',
    'forbidden'         => 'Bạn không được phép thực hiện thao tác này.',
    'server_error'      => 'Lỗi máy chủ, vui lòng thử lại sau.',

    // Lỗi xác thực (Validation)
    'validation_with'   => 'Một hoặc nhiều mối quan hệ được yêu cầu không hợp lệ.',
    'validation_data'   => 'Dữ liệu :attribute phải là :type.',
    'validation_fields' => 'Một hoặc nhiều trường được yêu cầu không hợp lệ.',
    'validation_failed' => 'Dữ liệu không hợp lệ.',
    'required'          => 'Trường :attribute là bắt buộc.',
    'email'             => 'Trường :attribute phải là địa chỉ email hợp lệ.',
    'max_length'        => 'Trường :attribute không được dài hơn :max ký tự.',
    'min_length'        => 'Trường :attribute phải chứa ít nhất :min ký tự.',
    'unique'            => 'Trường :attribute đã tồn tại.',
    'exists'            => 'Trường :attribute không tồn tại.',
    'array'             => 'Trường :attribute phải là một mảng .',
    'password_mismatch' => 'Mật khẩu không khớp.',
    'numeric'           => 'Trường :attribute phải là các loại đã được chỉ định.',
    'in'                => 'Trường :attribute không hợp lệ. Vui lòng chọn một trong các giá trị đã đưa ra.',
    'alpha_num'         => 'Trường :attribute chỉ chấp nhận các ký tự chữ và số (không có ký tự đặc biệt).',

    'conflict' => [
        'account_exists'      => 'Tài khoản đã tồn tại.',
        'email_taken'         => 'Email đã được sử dụng để đăng ký tài khoản. Vui lòng sử dụng email khác hoặc đăng nhập.',
        'action_conflict'     => 'Hành động này không thể thực hiện vì có xung đột với dữ liệu hiện tại.',
        'pending_action'      => 'Bạn đã có thao tác chờ xử lý. Vui lòng hủy thao tác trước đó để tiếp tục.',
        'subscription_exists' => 'Bạn đã có gói đăng ký đang hoạt động. Vui lòng hủy gói hiện tại trước khi đăng ký gói mới.',
        'duplicate_request'   => 'Yêu cầu đã được thực hiện trước đó. Vui lòng chờ xử lý hoặc thử lại sau.',
        'already_subscribed'  => 'Bạn đã đăng ký dịch vụ này. Không thể thực hiện lại thao tác.',
    ],

    'no_data'        => 'Không có dữ liệu nào được tìm thấy.',
    'data_not_owned' => 'Dữ liệu này không thuộc về bạn.',
];
