<?php

use Illuminate\Support\Facades\Route;
use Workable\HRM\Http\Controllers\Api\AttendanceController;
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

    Route::prefix('rules')->group(function () {
        Route::get('/', [PenaltyRuleController::class, 'index'])->name('api.rules.index');
        Route::get('/{id}', [PenaltyRuleController::class, 'show'])->name('api.rules.show');
        Route::post('', [PenaltyRuleController::class, 'store'])->name('api.rules.store');
        Route::post('/{id}', [PenaltyRuleController::class, 'update'])->name('api.rules.update');
        Route::delete('/{id}', [PenaltyRuleController::class, 'destroy'])->name('api.rules.destroy');
    });

    Route::prefix('fines')->group(function () {
        Route::get('/', [PenaltyController::class, 'index'])->name('api.fines.index');
        Route::get('/{id}', [PenaltyController::class, 'show'])->name('api.fines.show');
        Route::post('', [PenaltyController::class, 'store'])->name('api.fines.store');
        Route::post('/{id}', [PenaltyController::class, 'update'])->name('api.fines.update');
        Route::delete('/{id}', [PenaltyController::class, 'destroy'])->name('api.fines.destroy');
    });
});
