<?php

namespace Workable\UserTenant\Enums;

class TenantEnum
{
    const MALE   = '1';
    const FEMALE = '2';
    const OTHER  = '3';

    const STATUS_ACTIVE   = 1;
    const STATUS_INACTIVE = 2;
    const STATUS_BLOCKED  = 3;

    const GENDER = [
        self::MALE   => 'male',
        self::FEMALE => 'female',
        self::OTHER  => 'other',
    ];

    const STATUS = [
        self::STATUS_ACTIVE   => 'active',
        self::STATUS_INACTIVE => 'inactive',
        self::STATUS_BLOCKED  => 'blocked',
    ];

    public static function getStatus($status = null)
    {
        return isset($status) ? __('user-tenant::tenant.status_text.' . TenantEnum::STATUS[$status]) : null;
    }

    public static function getGender($gender)
    {
        return isset($gender) ? __('user-tenant::tenant.gender_text.' . TenantEnum::GENDER[$gender]) : null;
    }

    public static function convertDate($date)
    {
        return isset($date) ? $date->format('d-m-Y') : null;
    }

}
