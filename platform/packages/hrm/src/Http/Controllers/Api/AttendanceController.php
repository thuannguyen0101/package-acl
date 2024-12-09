<?php

namespace Workable\HRM\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Workable\HRM\Enums\ResponseEnum;
use Workable\HRM\Exports\AttendanceTemplateExport;
use Workable\HRM\Http\Requests\AttendanceCreateRequest;
use Workable\HRM\Http\Requests\AttendanceRequest;
use Workable\HRM\Imports\AttendancesImport;
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
//        list(
//            'status' => $status,
//            'message' => $message,
//            'attendance' => $attendance,
//            ) = $this->attendanceService->index($request->all());
//
//        if ($status != ResponseEnum::CODE_OK) {
//            return $this->respondError($message);
//        }
//
//        return $this->respondSuccess($message, $attendance);
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

    public function getUserAttendanceByMonth(AttendanceRequest $request)
    {
        list(
            'status' => $status,
            'message' => $message,
            'attendance' => $attendance,
            ) = $this->attendanceService->getUserAttendanceByMonth($request->all());

        if ($status != ResponseEnum::CODE_OK) {
            return $this->respondError($message);
        }

        return $this->respondSuccess($message, $attendance);
    }

    public function getUsersAttendanceByMonth(AttendanceRequest $request)
    {
        list(
            'status' => $status,
            'message' => $message,
            'attendances' => $attendances,
            ) = $this->attendanceService->getUsersAttendanceByMonth($request->all());

        if ($status != ResponseEnum::CODE_OK) {
            return $this->respondError($message);
        }

        return $this->respondSuccess($message, $attendances);
    }

    public function exportTemplate()
    {
        $fileName = 'attendance_template.xlsx';
        return Excel::download(new AttendanceTemplateExport, $fileName);
    }

    public function importTemplate(Request $request)
    {
        $import = new AttendancesImport();
        Excel::import($import, $request->file('file'));
        list(
            'status' => $status,
            'message' => $message,
            'data' => $data,
            ) = $import->getResult();
        if ($status != ResponseEnum::CODE_OK) {
            return $this->respondError($message, $data);
        }

        return $this->respondSuccess($message, $data);
    }
}
