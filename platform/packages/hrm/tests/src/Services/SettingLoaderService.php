<?php

namespace Workable\HRM\Services;

use Workable\HRM\Models\TenantSetting;

class SettingLoaderService
{
    protected static $settingAttendance = [];
    protected $tenantId;
    protected $settings;

    private function __construct($tenantId)
    {
        $this->tenantId = $tenantId;
        $this->settings = $this->loadSettingsFromDB($tenantId);
    }

    public static function getInstance($tenantId)
    {
        if (!isset(self::$settingAttendance[$tenantId])) {
            self::$settingAttendance[$tenantId] = new self($tenantId);
        }
        return self::$settingAttendance[$tenantId];
    }

    private function loadSettingsFromDB($tenantId)
    {
        $settings = TenantSetting::query()
            ->where('tenant_id', $tenantId)
            ->value('setting_attendance');

        return $settings ? json_decode($settings, true) : config('hrm.attendance_time');;
    }

    public function refresh()
    {
        $this->settings = $this->loadSettingsFromDB($this->tenantId);
    }

    public function get($key = null, $default = null)
    {
        if ($key === null) {
            return $this->settings;
        }

        return $this->settings[$key] ?? $default;
    }
}
