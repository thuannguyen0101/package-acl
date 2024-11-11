<?php

use Illuminate\Support\Facades\Route;
use Workable\UserTenant\Http\Controllers\Api\TenantController;
use Workable\UserTenant\Http\Controllers\Api\UserController;


Route::group([
    'prefix' => 'api/v1',
], function () {
    Route::group(['prefix' => 'tenants'], function () {
        Route::get('/', [TenantController::class, 'index'])->name('api.tenants.index');
        Route::post('/', [TenantController::class, 'store'])->name('api.tenants.store');
        Route::put('/{id}', [TenantController::class, 'update'])->name('api.tenants.update');
        Route::get('/{id}', [TenantController::class, 'show'])->name('api.tenants.show');
        Route::delete('/{id}', [TenantController::class, 'destroy'])->name('api.tenants.destroy');
    });

    Route::group(['prefix' => 'users'], function () {
        Route::get('/', [UserController::class, 'index'])->name('api.users.index');
        Route::post('/', [UserController::class, 'store'])->name('api.users.store');
        Route::put('/{id}', [UserController::class, 'update'])->name('api.users.update');
        Route::get('/{id}', [UserController::class, 'show'])->name('api.users.show');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('api.users.destroy');
    });
});
