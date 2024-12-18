<?php

use Illuminate\Support\Facades\Route;
use Workable\HRM\Http\Controllers\Api\AttendanceController;
use Workable\HRM\Http\Controllers\Api\InsuranceController;
use Workable\HRM\Http\Controllers\Api\LeaveRequestController;
use Workable\HRM\Http\Controllers\Api\TenantSettingAttendanceController;
use Workable\HRM\Http\Controllers\Api\PenaltyController;
use Workable\HRM\Http\Controllers\Api\PenaltyRuleController;

Route::group([
    'prefix'     => 'api/v1',
    'middleware' => config('acl.auth.middleware'),
], function () {
    //     feature
    Route::prefix('attendances')->group(function () {
//        Route::get('/', [AttendanceController::class, 'index'])->name('api.attendances.index');
        Route::get('/user', [AttendanceController::class, 'getUserAttendanceByMonth'])->name('api.attendances.user');
        Route::get('/manager', [AttendanceController::class, 'getUsersAttendanceByMonth'])->name('api.attendances.manager');
        Route::get('/{id}', [AttendanceController::class, 'show'])->name('api.attendances.show');
        Route::post('/import', [AttendanceController::class, 'importTemplate'])->name('api.attendances.import');
        Route::post('/', [AttendanceController::class, 'store'])->name('api.attendances.store');
        Route::post('/attendance', [AttendanceController::class, 'markAttendance'])->name('api.attendances.mark_attendance');
        Route::post('/{id}', [AttendanceController::class, 'update'])->name('api.attendances.update');

        Route::get('/export/template', [AttendanceController::class, 'exportTemplate'])->name('api.attendances.export.template');

        Route::delete('/{id}', [AttendanceController::class, 'destroy'])->name('api.attendances.destroy');
    });

    Route::prefix('config')->group(function () {
        Route::prefix('attendance')->group(function () {
            Route::get('/{id}', [TenantSettingAttendanceController::class, 'show'])->name('api.config.attendance.show');
            Route::post('', [TenantSettingAttendanceController::class, 'store'])->name('api.config.attendance.store');
            Route::post('/{id}', [TenantSettingAttendanceController::class, 'update'])->name('api.config.attendance.update');
            Route::delete('/{id}', [TenantSettingAttendanceController::class, 'destroy'])->name('api.config.attendance.destroy');
        });
    });

    Route::prefix('penalty-rules')->group(function () {
        Route::get('/', [PenaltyRuleController::class, 'index'])->name('api.penalty_rules.index');
        Route::get('/{id}', [PenaltyRuleController::class, 'show'])->name('api.penalty_rules.show');
        Route::post('', [PenaltyRuleController::class, 'store'])->name('api.penalty_rules.store');
        Route::post('/{id}', [PenaltyRuleController::class, 'update'])->name('api.penalty_rules.update');
        Route::delete('/{id}', [PenaltyRuleController::class, 'destroy'])->name('api.penalty_rules.destroy');
    });

    Route::prefix('penalties')->group(function () {
        Route::get('/', [PenaltyController::class, 'index'])->name('api.penalties.index');
        Route::get('/{id}', [PenaltyController::class, 'show'])->name('api.penalties.show');
        Route::post('', [PenaltyController::class, 'store'])->name('api.penalties.store');
        Route::post('/{id}', [PenaltyController::class, 'update'])->name('api.penalties.update');
        Route::delete('/{id}', [PenaltyController::class, 'destroy'])->name('api.penalties.destroy');
    });

    Route::prefix('leave-request')->group(function () {
        Route::get('/user', [LeaveRequestController::class, 'getUser'])->name('api.leave-request.get_user');
        Route::get('/', [LeaveRequestController::class, 'index'])->name('api.leave-request.index');
        Route::get('/{id}', [LeaveRequestController::class, 'show'])->name('api.leave-request.show');
        Route::post('', [LeaveRequestController::class, 'store'])->name('api.leave-request.store');
        Route::post('/{id}', [LeaveRequestController::class, 'update'])->name('api.leave-request.update');
        Route::delete('/{id}', [LeaveRequestController::class, 'destroy'])->name('api.leave-request.destroy');
    });

    Route::prefix('insurances')->group(function () {
        Route::get('/', [InsuranceController::class, 'index'])->name('api.insurances.index');
        Route::get('/{id}', [InsuranceController::class, 'show'])->name('api.insurances.show');
        Route::post('', [InsuranceController::class, 'store'])->name('api.insurances.store');
        Route::post('/{id}', [InsuranceController::class, 'update'])->name('api.insurances.update');
        Route::delete('/{id}', [InsuranceController::class, 'destroy'])->name('api.insurances.destroy');
    });
});
