<?php

use Illuminate\Support\Facades\Route;
use Workable\UserTenant\Http\Controllers\Api\TenantController;


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
});
