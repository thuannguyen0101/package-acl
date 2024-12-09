<?php

use Illuminate\Support\Facades\Route;
use Workable\HRM\Http\Controllers\Api\AttendanceController;

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
});
