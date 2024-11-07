<?php
return [
    // Thông báo thành công
    'success'                => 'Thao tác thành công.',
    'created'                => 'Tạo mới thành công.',
    'updated'                => 'Cập nhật thành công.',
    'deleted'                => 'Xóa thành công.',

    // Lỗi chung
    'error'                  => 'Đã xảy ra lỗi, vui lòng thử lại sau.',
    'not_found'              => 'Không tìm thấy tài nguyên yêu cầu.',
    'unauthorized'           => 'Bạn không có quyền truy cập.',
    'forbidden'              => 'Bạn không được phép thực hiện thao tác này.',
    'server_error'           => 'Lỗi máy chủ, vui lòng thử lại sau.',

    // Lỗi xác thực (Validation)
    'validation_failed'      => 'Dữ liệu không hợp lệ.',
    'required'               => 'Trường :attribute là bắt buộc.',
    'email'                  => 'Trường :attribute phải là địa chỉ email hợp lệ.',
    'max_length'             => 'Trường :attribute không được dài hơn :max ký tự.',
    'min_length'             => 'Trường :attribute phải chứa ít nhất :min ký tự.',
    'unique'                 => 'Trường :attribute đã tồn tại.',
    'exists'                 => 'Trường :attribute không tồn tại.',
    'password_mismatch'      => 'Mật khẩu không khớp.',

    // Xác thực người dùng (Authentication)
    'login_failed'           => 'Đăng nhập không thành công, vui lòng kiểm tra lại thông tin đăng nhập.',
    'login_success'          => 'Đăng nhập thành công.',
    'logout_success'         => 'Đăng xuất thành công.',
    'register_success'       => 'Đăng ký thành công.',
    'password_reset_success' => 'Mật khẩu đã được đặt lại thành công.',
    'password_reset_failed'  => 'Đặt lại mật khẩu không thành công.',
    'token_expired'          => 'Mã xác thực đã hết hạn.',
    'token_invalid'          => 'Mã xác thực không hợp lệ.',
    'account_disabled'       => 'Tài khoản của bạn đã bị vô hiệu hóa.',

    // Quyền truy cập (Authorization)
    'no_permission'          => 'Bạn không có quyền thực hiện hành động này.',

    // Thao tác với dữ liệu
    'data_saved'             => 'Dữ liệu đã được lưu thành công.',
    'data_updated'           => 'Dữ liệu đã được cập nhật thành công.',
    'data_deleted'           => 'Dữ liệu đã được xóa thành công.',
    'data_not_found'         => 'Không tìm thấy dữ liệu.',

    // Các lỗi khác
    'too_many_requests'      => 'Bạn đã gửi quá nhiều yêu cầu. Vui lòng thử lại sau :seconds giây.',
    'action_not_allowed'     => 'Thao tác không được phép.',
    'invalid_credentials'    => 'Thông tin đăng nhập không chính xác.',
    'csrf_token_mismatch'    => 'Yêu cầu không hợp lệ do mã CSRF không khớp.',

    // Các thông báo khác...

    'no_data' => 'Không có dữ liệu nào được tìm thấy.',

    // Các thông báo khác...

    // permission
    'permission'             => [
        'message_not_found' => 'Không có dữ liệu.',
        'message_updated'   => 'Dữ liệu đã được cập nhật thành công.'
    ],
];
