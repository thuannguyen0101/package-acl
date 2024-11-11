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
    'unauthorized'           => 'Bạn không có quyền truy cập vào tài nguyên này. Vui lòng xác thực trước khi tiếp tục.',
    'forbidden'              => 'Bạn không được phép thực hiện thao tác này.',
    'server_error'           => 'Lỗi máy chủ, vui lòng thử lại sau.',


    // Lỗi xác thực (Validation)
    'validation_with'        => 'Một hoặc nhiều mối quan hệ được yêu cầu không hợp lệ.',
    'validation_data'        => 'Dữ liệu :attribute phải là :type.',
    'validation_fields'      => 'Một hoặc nhiều trường được yêu cầu không hợp lệ.',
    'validation_failed'      => 'Dữ liệu không hợp lệ.',
    'required'               => 'Trường :attribute là bắt buộc.',
    'email'                  => 'Trường :attribute phải là địa chỉ email hợp lệ.',
    'max_length'             => 'Trường :attribute không được dài hơn :max ký tự.',
    'min_length'             => 'Trường :attribute phải chứa ít nhất :min ký tự.',
    'unique'                 => 'Trường :attribute đã tồn tại.',
    'exists'                 => 'Trường :attribute không tồn tại.',
    'array'                  => 'Trường :attribute phải là một mảng .',
    'password_mismatch'      => 'Mật khẩu không khớp.',
    'numeric'                => 'Trường :attribute phải là các loại đã được chỉ định.',
    'in'                     => 'Trường :attribute không hợp lệ. Vui lòng chọn một trong các giá trị đã đưa ra.',

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
    'account_not_owned'      => 'Tài khoản này không thuộc về bạn.',
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

    'conflict' => [
        'account_exists' => 'Tài khoản đã tồn tại.',

        'email_taken'         => 'Email đã được sử dụng để đăng ký tài khoản. Vui lòng sử dụng email khác hoặc đăng nhập.',
        'action_conflict'     => 'Hành động này không thể thực hiện vì có xung đột với dữ liệu hiện tại.',
        'pending_action'      => 'Bạn đã có thao tác chờ xử lý. Vui lòng hủy thao tác trước đó để tiếp tục.',
        'subscription_exists' => 'Bạn đã có gói đăng ký đang hoạt động. Vui lòng hủy gói hiện tại trước khi đăng ký gói mới.',
        'duplicate_request'   => 'Yêu cầu đã được thực hiện trước đó. Vui lòng chờ xử lý hoặc thử lại sau.',
        'already_subscribed'  => 'Bạn đã đăng ký dịch vụ này. Không thể thực hiện lại thao tác.',
    ],


    // Các thông báo khác...

    'no_data'    => 'Không có dữ liệu nào được tìm thấy.',

    // Các thông báo khác...

    // permission
    'permission' => [
        'not_found'         => 'Không tìm thấy quyền.',
        'message_not_found' => 'Không có dữ liệu.',
        'message_updated'   => 'Dữ liệu đã được cập nhật thành công.'
    ],

    'user' => [
        'not_found' => 'Không tìm thấy người dùng.'
    ],

    'role' => [
        'not_found' => 'Không tìm thấy vai trò.'
    ]
];
