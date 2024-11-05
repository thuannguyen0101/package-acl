<?php

use Illuminate\Support\Facades\Route;
use Workable\Bank\Http\Controllers\Api\Account\AccountController;

Route::group([
    'prefix'     => 'api/v1',
    'middleware' => 'acl_jwt_check'
], function () {
    // feature
    Route::prefix('account')->group(function () {
        Route::get('/', [AccountController::class, 'index'])->name('api.account.index');
        Route::post('/', [AccountController::class, 'store'])->name('api.account.store');
        Route::get('/{id}', [AccountController::class, 'show'])->name('api.account.show');
        Route::put('/{id}', [AccountController::class, 'update'])->name('api.account.update');
        Route::delete('/{id}', [AccountController::class, 'destroy'])->name('api.account.destroy');
    });
});
