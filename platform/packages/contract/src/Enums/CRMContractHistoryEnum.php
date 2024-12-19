<?php

namespace Workable\Contract\Enums;

class CRMContractHistoryEnum
{
    const CREATED = 1;     // Tạo mới

    const UPDATED = 2;     // Cập nhật

    const DELETE = 3;     // Xóa


    const STATUS_TEXT = [
        self::CREATED => 'Tạo mới',
        self::UPDATED => 'Cập nhật',
        self::DELETE  => 'Xóa',
    ];
}
