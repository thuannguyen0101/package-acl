<?php

namespace Workable\HRM\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Workable\HRM\Enums\ResponseEnum;
use Workable\HRM\Http\Requests\AttendanceCreateRequest;
use Workable\HRM\Http\Requests\AttendanceRequest;
use Workable\HRM\Services\AttendanceService;
use Workable\Support\Traits\ResponseHelperTrait;

class AttendanceController extends Controller
{
    use ResponseHelperTrait;

    protected $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
//        $this->middleware('acl_permission:account_money_list')->only('index');
//        $this->middleware('acl_permission:account_money_create')->only('store');
//        $this->middleware('acl_permission:account_money_update')->only('show');
//        $this->middleware('acl_permission:account_money_show')->only('update');
//        $this->middleware('acl_permission:account_money_delete')->only('destroy');

        $this->attendanceService = $attendanceService;
    }

    public function index(AttendanceRequest $request)
    {
        list(
            'status' => $status,
            'message' => $message,
            'account_monies' => $account_monies,
            ) = $this->attendanceService->index($request->all());

        return $this->respondSuccess($message, $account_monies);
    }

    public function markAttendance(AttendanceRequest $request)
    {
        list(
            'status' => $status,
            'message' => $message,
            'attendance' => $attendance,
            ) = $this->attendanceService->markAttendance($request->all());

        if ($status != ResponseEnum::CODE_OK) {
            return $this->respondError($message);
        }

        return $this->respondSuccess($message, $attendance);
    }

    public function store(AttendanceCreateRequest $request)
    {
        list(
            'status' => $status,
            'message' => $message,
            'attendance' => $attendance,
            ) = $this->attendanceService->store($request->all());

        if ($status != ResponseEnum::CODE_OK) {
            return $this->respondError($message);
        }

        return $this->respondSuccess($message, $attendance);
    }

    public function show($id, AttendanceRequest $request)
    {
        list(
            'status' => $status,
            'message' => $message,
            'attendance' => $attendance,
            ) = $this->attendanceService->show($id, $request->all());

        if ($status != ResponseEnum::CODE_OK) {
            return $this->respondError($message);
        }

        return $this->respondSuccess($message, $attendance);
    }

    public function update(int $id, AttendanceCreateRequest $request)
    {
        $data = $request->validated();
        list(
            'status' => $status,
            'message' => $message,
            'attendance' => $attendance,
            ) = $this->attendanceService->update($id, $data);

        if ($status != ResponseEnum::CODE_OK) {
            return $this->respondError($message);
        }

        return $this->respondSuccess($message, $attendance);
    }

    public function destroy(int $id)
    {
        list(
            'status' => $status,
            'message' => $message,
            ) = $this->attendanceService->destroy($id);

        if ($status != ResponseEnum::CODE_OK) {
            return $this->respondError($message);
        }

        return $this->respondSuccess($message);
    }
}
