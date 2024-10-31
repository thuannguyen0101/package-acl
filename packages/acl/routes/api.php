<?php

use Illuminate\Support\Facades\Route;
use Workable\ACL\Http\Controllers\Api\Account\AccountController;
use Workable\ACL\Http\Controllers\Api\Admin\PermissionController;
use Workable\ACL\Http\Controllers\Api\Admin\RoleController;
use Workable\ACL\Http\Controllers\Api\Auth\AuthAPIController;

Route::group([
    'prefix' => 'api/v1',
], function () {
    Route::group(['prefix' => 'permissions'], function () {
        Route::get('/', [PermissionController::class, 'index'])->name('api.permission.index');
        Route::get('/{id}', [PermissionController::class, 'show'])->name('api.permission.show');
        Route::put('/update', [PermissionController::class, 'update'])->name('api.permission.update');
    });
    Route::group(['prefix' => 'roles'], function () {
        Route::get('/', [RoleController::class, 'index'])->name('api.role.index');
        Route::post('/create', [RoleController::class, 'store'])->name('api.role.store');
        Route::get('/{id}', [RoleController::class, 'show'])->name('api.role.show');
        Route::put('/update', [RoleController::class, 'save'])->name('api.role.save');
        Route::delete('/{id}', [RoleController::class, 'destroy'])->name('api.role.destroy');
    });


//    Route::post('/login', [AuthAPIController::class, 'login'])->name('api.auth.login');
//    Route::post('/register', [AuthAPIController::class, 'register'])->name('api.auth.register');
//    Route::post('/', [AuthAPIController::class, 'logout'])->name('api.auth.logout');
//
//    // feature
//    Route::group(['middleware' => 'jwtCheck'], function () {
//        Route::group(['prefix' => 'user'], function () {
//
//            // account
//            Route::prefix('/account')->group(function () {
//                Route::post('/', [AccountController::class, 'store'])->name('api.user.account.store');
//                Route::get('/', [AccountController::class, 'show'])->name('api.user.account.show');
//                Route::put('/update', [AccountController::class, 'save'])->name('api.user.account.save');
//                Route::delete('/delete', [AccountController::class, 'destroy'])->name('api.user.account.destroy');
//            });
//        });
//        Route::group(['prefix' => 'admin'], function () {
//            Route::prefix('/account')->group(function () {
//                Route::get('/', [AccountController::class, 'index'])->name('api.admin.account.index');
//            });
//        });
//    });
});
