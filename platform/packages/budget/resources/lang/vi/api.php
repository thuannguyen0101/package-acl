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

    'fields'       => [
        'string'   => 'chuỗi',
        'email'    => 'email',
        'numeric'  => 'số',
        'checkbox' => 'một trong các lựa chọn đã đưa ra. ',

        'name'      => 'tên của chủ sở hữu',
        'username'  => 'tên đăng nhập của người dùng',
        'avatar'    => 'dường dẫn hình ảnh',
        'password'  => 'mật khẩu',
        'phone'     => 'số điện thoại',
        'status'    => 'trạng thái',
        'filters'   => 'bộ lọc',
        'filters.*' => 'giá trị bộ lọc',
        'role_id'   => 'vai trò',
        'model_id'  => 'người dùng',
        'tenant_id' => 'chủ sở hữu'
    ],

    // Thông báo thành công
    'success'      => 'Thao tác thành công.',
    'created'      => 'Tạo mới thành công.',
    'updated'      => 'Cập nhật thành công.',
    'deleted'      => 'Xóa thành công.',

    // Lỗi chung
    'error'        => 'Đã xảy ra lỗi, vui lòng thử lại sau.',
    'not_found'    => 'Không tìm thấy tài nguyên yêu cầu.',
    'unauthorized' => 'Bạn không có quyền truy cập vào tài nguyên này. Vui lòng xác thực trước khi tiếp tục.',
    'forbidden'    => 'Bạn không được phép thực hiện thao tác này.',
    'server_error' => 'Lỗi máy chủ, vui lòng thử lại sau.',

    'not_data'     => [
        'account_money'       => 'Không có khoản quỹ nào tồn tại.',
        'expense_categories ' => 'Không có danh mục thu chi nào tồn tại.'
    ]
];
