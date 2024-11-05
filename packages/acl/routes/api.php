<?php

use Illuminate\Support\Facades\Route;
use Workable\ACL\Http\Controllers\Api\Admin\PermissionController;
use Workable\ACL\Http\Controllers\Api\Admin\RoleController;
use Workable\ACL\Http\Controllers\Api\Auth\AuthAPIController;

Route::group([
    'prefix' => 'api/v1',

], function () {
    Route::post('/login', [AuthAPIController::class, 'login'])->name('api.auth.login');
    Route::post('/register', [AuthAPIController::class, 'register'])->name('api.auth.register');

    Route::group(['prefix' => 'permissions'], function () {
        Route::get('/', [PermissionController::class, 'index'])->name('api.permission.index');
        Route::put('/{id}', [PermissionController::class, 'update'])->name('api.permission.update');
        Route::get('/{id}', [PermissionController::class, 'show'])->name('api.permission.show');
    });

    Route::group(['prefix' => 'roles'], function () {
        Route::get('/', [RoleController::class, 'index'])->name('api.role.index');
        Route::delete('/{id}', [RoleController::class, 'destroy'])->name('api.role.destroy');
        Route::post('/', [RoleController::class, 'store'])->name('api.role.store');
        Route::get('/{id}', [RoleController::class, 'show'])->name('api.role.show');
        Route::put('/{id}', [RoleController::class, 'update'])->name('api.role.update');
        Route::post('/assign-model', [RoleController::class, 'assignRoleForModel'])->name('api.role.assign-model');
        //        Route::post('/assign', [RoleController::class, 'assignRole'])->name('api.role.assign');
    });
});
