<?php

namespace Workable\ACL\Enums;

class ResponseMessageEnum
{
    const CODE_OK                    = 200;
    const CODE_CREATED               = 201;
    const CODE_ACCEPTED              = 202;
    const CODE_NO_CONTENT            = 204;
    const CODE_BAD_REQUEST           = 400;
    const CODE_UNAUTHORIZED          = 401;
    const CODE_FORBIDDEN             = 403;
    const CODE_NOT_FOUND             = 404;
    const CODE_CONFLICT              = 409;
    const CODE_UNPROCESSABLE_ENTITY  = 422;
    const CODE_INTERNAL_SERVER_ERROR = 500;
    const CODE_GATEWAY_TIMEOUT       = 504;

    const MESSAGE_TEXT = [
        self::CODE_OK                    => 'Yêu cầu đã được thực hiện thành công.',
        self::CODE_CREATED               => 'Tạo mới thành công.',
        self::CODE_ACCEPTED              => 'Yêu cầu đã được chấp nhận và đang được xử lý.',
        self::CODE_NO_CONTENT            => 'Dữ liệu đã được xóa thành công.',
        self::CODE_BAD_REQUEST           => 'Yêu cầu không hợp lệ, vui lòng kiểm tra lại dữ liệu gửi lên.',
        self::CODE_UNAUTHORIZED          => 'Người dùng chưa đăng nhập.',
        self::CODE_FORBIDDEN             => 'Bạn không có quyền thực hiện hành động này.',
        self::CODE_NOT_FOUND             => 'Tài nguyên không tồn tại.',
        self::CODE_UNPROCESSABLE_ENTITY  => 'Dữ liệu không hợp lệ, vui lòng kiểm tra và thử lại.',
        self::CODE_INTERNAL_SERVER_ERROR => 'Hệ thống đang gặp sự cố, vui lòng thử lại sau.',
        self::CODE_GATEWAY_TIMEOUT       => 'Máy chủ không phản hồi kịp thời, vui lòng thử lại sau.'
    ];
}
