<?php

namespace Workable\Bank\Enums;

class AccountEnum
{
    // TYPE
    const TYPE_SAVINGS_ACCOUNT    = 1;
    const TYPE_CHECKING_ACCOUNT   = 2;
    const TYPE_INVESTMENT_ACCOUNT = 3;

    const TYPE_TEXT = [
        self::TYPE_SAVINGS_ACCOUNT    => 'Tiết Kiệm',
        self::TYPE_CHECKING_ACCOUNT   => 'Thanh toán',
        self::TYPE_INVESTMENT_ACCOUNT => 'Đầu tư',
    ];

    // BANK_NAME
    const BANK_NAME_BIDV        = 1;
    const BANK_NAME_VIETCOMBANK = 2;
    const BANK_NAME_VIETINBANK  = 3;
    const BANK_NAME_MBBANK      = 4;
    const BANK_NAME_TECHCOMBANK = 5;

    const BANK_NAME_TEXT = [
        self::BANK_NAME_BIDV        => 'BIDV',
        self::BANK_NAME_VIETCOMBANK => 'Vietcombank',
        self::BANK_NAME_VIETINBANK  => 'VietinBank',
        self::BANK_NAME_MBBANK      => 'MB Bank',
        self::BANK_NAME_TECHCOMBANK => 'Techcombank'
    ];

    // BRANCH_NAME
    const BRANCH_NAME_HN  = 1;
    const BRANCH_NAME_HCM = 2;

    const BRANCH_NAME_TEXT = [
        self::BRANCH_NAME_HN  => 'TP. Hà Nội',
        self::BRANCH_NAME_HCM => 'TP. Hồ Chí Minh',
    ];

    // STATUS
    const STATUS_ACTIVE   = 1;
    const STATUS_INACTIVE = 2;
    const STATUS_CLOSED   = 3;
    const STATUS_TEXT     = [
        self::STATUS_ACTIVE   => 'Hoạt động',
        self::STATUS_INACTIVE => 'Không hoạt động',
        self::STATUS_CLOSED   => 'Đã đóng',
    ];
}
