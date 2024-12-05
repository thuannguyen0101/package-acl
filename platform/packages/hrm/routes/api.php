<?php

use Illuminate\Support\Facades\Route;
use Workable\HRM\Http\Controllers\Api\AttendanceController;

Route::group([
    'prefix'     => 'api/v1',
    'middleware' => config('acl.auth.middleware'),
], function () {
//     feature
    Route::prefix('attendances')->group(function () {
        Route::get('/', [AttendanceController::class, 'index'])->name('api.attendances.index');
        Route::post('/', [AttendanceController::class, 'store'])->name('api.attendances.store');
        Route::post('/attendance', [AttendanceController::class, 'markAttendance'])->name('api.attendances.mark_attendance');
        Route::get('/{id}', [AttendanceController::class, 'show'])->name('api.attendances.show');
        Route::post('/{id}', [AttendanceController::class, 'update'])->name('api.attendances.update');

        Route::delete('/{id}', [AttendanceController::class, 'destroy'])->name('api.attendances.destroy');
    });
});
