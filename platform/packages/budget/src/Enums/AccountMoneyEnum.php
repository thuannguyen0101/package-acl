<?php

namespace Workable\Budget\Enums;

use Carbon\Carbon;

class AccountMoneyEnum
{
    public static function convertDate($date): ?string
    {
        return isset($date) ? Carbon::parse($date)->format('Y-m-d') : null;
    }
}
