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

    const SIZE_TEXT_ARRAY = [
        1 => "under_10",
        2 => "10_25",
        3 => "25_50",
        4 => "50_100",
        5 => "100_200",
        6 => "200_500",
        7 => "500_1000",
        8 => "above_1000",
    ];

    const WORK_DAY_TEXT_ARRAY = [
        1  => "monday_friday",
        2  => "monday_friday_morning",
        3  => "monday-saturday",
        4  => "full_week",
        5  => "flexible",
        10 => "other",
    ];

    const LEVEL_TEXT_ARRAY = [
        1  => "Staff",
        2  => "Team Leader",
        3  => "Deputy Manager",
        4  => "Manager",
        5  => "Deputy Director",
        6  => "Director",
        7  => "CEO",
        10 => "Other",
    ];

    public static function getStatus($status = null): array
    {
        return self::getDataEnum($status, self::STATUS, "user-tenant::api.status_text");
    }

    public static function getSize($size = null): array
    {
        return self::getDataEnum($size, self::SIZE_TEXT_ARRAY, "user-tenant::api.size_text");
    }

    public static function getGender($gender = null): array
    {
        return self::getDataEnum($gender, self::GENDER, "user-tenant::api.gender_text");
    }
    public static function getWordDay($workDay = null): array
    {
        return self::getDataEnum($workDay, self::WORK_DAY_TEXT_ARRAY, "user-tenant::api.work_day_text");
    }

    public static function getLevel($level = null): array
    {
        return self::getDataEnum($level, self::LEVEL_TEXT_ARRAY, "user-tenant::api.work_day_text");
    }

    public static function getMetaAttribute(string $metaAttribute = null): array
    {
        if (!isset($metaAttribute)) {
            return [
                "established" => null,
                "work_day"    => null,
                "uniform"     => null,
                "skype"       => null,
                "position"    => null,
            ];
        }
    }

    public static function getDataEnum($field = null, $arrayEnum = null, $keyLang = null): array
    {
        $data = [
            'id'    => null,
            'value' => null
        ];

        if (isset($field)) {
            $data = [
                'id'    => $field,
                'value' => __("$keyLang." . $arrayEnum[$field])
            ];
        }

        return $data;
    }

    public static function convertDate($date)
    {
        return isset($date) ? $date->format('d-m-Y') : null;
    }

}
