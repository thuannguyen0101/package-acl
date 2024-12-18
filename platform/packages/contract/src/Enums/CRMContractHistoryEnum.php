<?php

namespace Workable\Contract\Enums;

class CRMContractHistoryEnum
{
    const UPDATE = 1;     // Cập nhật

    const DELETE           = 2;     // Xóa

    const CONTRACT_PAYMENT = 3;     // Thanh toán

    const STATUS_TEXT = [
        self::UPDATE           => 'Cập nhật',
        self::DELETE           => 'Xóa',
        self::CONTRACT_PAYMENT => 'Thanh toán',
    ];
}
