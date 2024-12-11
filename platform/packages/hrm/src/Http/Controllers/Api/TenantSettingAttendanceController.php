<?php

namespace Workable\HRM\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Workable\HRM\Enums\ResponseEnum;
use Workable\HRM\Http\Requests\TenantSettingAttendanceRequest;
use Workable\HRM\Services\TenantSettingAttendanceService;
use Workable\Support\Traits\ResponseHelperTrait;

class TenantSettingAttendanceController extends Controller
{
    use ResponseHelperTrait;

    protected $service;

    public function __construct(TenantSettingAttendanceService $service)
    {
        $this->service = $service;
    }

    public function store(TenantSettingAttendanceRequest $request)
    {
        list(
            'status' => $status,
            'message' => $message,
            'config_attendance' => $config_attendance,
            ) = $this->service->store($request->all());

        if ($status != ResponseEnum::CODE_OK) {
            return $this->respondError($message);
        }

        return $this->respondSuccess($message, $config_attendance);
    }

    public function show(int $id, TenantSettingAttendanceRequest $request)
    {
        list(
            'status' => $status,
            'message' => $message,
            'config_attendance' => $config_attendance,
            ) = $this->service->show($id, $request->all());

        if ($status != ResponseEnum::CODE_OK) {
            return $this->respondError($message);
        }

        return $this->respondSuccess($message, $config_attendance);
    }

    public function update(int $id, TenantSettingAttendanceRequest $request)
    {
        list(
            'status' => $status,
            'message' => $message,
            'config_attendance' => $config_attendance,
            ) = $this->service->update($id, $request->all());

        if ($status != ResponseEnum::CODE_OK) {
            return $this->respondError($message);
        }

        return $this->respondSuccess($message, $config_attendance);
    }

    public function destroy(int $id)
    {
        list(
            'status' => $status,
            'message' => $message,
            'config_attendance' => $config_attendance,
            ) = $this->service->destroy($id);

        if ($status != ResponseEnum::CODE_OK) {
            return $this->respondError($message);
        }

        return $this->respondSuccess($message, $config_attendance);
    }
}
