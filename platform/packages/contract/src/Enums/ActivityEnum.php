<?php

namespace Workable\Contract\Enums;

class ActivityEnum
{
    const ADD_MEETING  = 1;
    const ADD_CALL     = 2;
    const SCHEDULE     = 3;
    const REQUEST_CALL = 4;

    const TYPE_ACTIVITY = [
        self::ADD_MEETING  => 'Thêm cuộc gặp',
        self::ADD_CALL     => 'Thêm cuộc gọi',
        self::SCHEDULE     => 'Đặt lịch',
        self::REQUEST_CALL => 'Yêu cầu gọi',
    ];
}
