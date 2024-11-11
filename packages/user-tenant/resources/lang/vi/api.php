<?php
return [
    'success'        => 'Thao tác thành công.',
    'created'        => 'Tạo mới thành công.',
    'updated'        => 'Cập nhật thành công.',
    'deleted'        => 'Xóa thành công.',
    'no_data'        => 'Không có dữ liệu nào được tìm thấy.',
    'data_not_found' => 'Không tìm thấy dữ liệu.',
    'not_found'      => 'Không tìm thấy tài nguyên yêu cầu.',

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
        'alpha_num'       => 'Trường :attribute chỉ chấp nhận các ký tự chữ và số (không có ký tự đặc biệt).'
    ],

    'fields' => [
        'string'   => 'chuỗi',
        'email'    => 'email',
        'numeric'  => 'số',
        'checkbox' => 'một trong các lựa chọn đã đưa ra. ',

        'name'       => 'tên của chủ sở hữu',
        'username'   => 'tên đăng nhập của người dùng',
        'avatar'     => 'dường dẫn hình ảnh',
        'password'   => 'mật khẩu ',
        'phone'      => 'số điện thoại',
        'status'     => 'trạng thái',
        'address'    => 'địa chỉ',
        'gender'     => 'giới tính',
        'birthday'   => 'ngày sinh',
        'size'       => 'quy mô sử dụng',
        'citizen_id' => 'căn cước công dân',
        'start_at'   => 'ngày bắt đầu sử dụng',
        'expiry_at'  => 'ngày kết thúc sử dụng'
    ],

    'status_text' => [
        'active'   => 'Hoạt động',
        'inactive' => 'Ngừng hoạt động',
        'blocked'  => 'Bị khóa'
    ],
    'gender_text' => [
        'male'   => 'Nam',
        'female' => 'Nữ',
        'other'  => 'Khác',
    ]
];
