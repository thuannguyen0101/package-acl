<?php

use Illuminate\Support\Facades\Route;
use Workable\ACL\Http\Controllers\Api\Account\AccountController;
use Workable\ACL\Http\Controllers\Api\Auth\AuthAPIController;

Route::group([
    'prefix' => 'api',
], function () {
    Route::prefix('/v1')->group(function () {
        // Auth
        Route::post('/login', [AuthAPIController::class, 'login'])->name('api.auth.login');
        Route::post('/register', [AuthAPIController::class, 'register'])->name('api.auth.register');

        // feature
        Route::group(['middleware' => 'jwtCheck'], function () {
            Route::group([
                'prefix' => 'account',
            ], function () {
                Route::get('/', [AccountController::class, 'index'])->name('api.account.index');
                Route::post('/', [AccountController::class, 'store'])->name('api.account.store');
                Route::get('/{id}', [AccountController::class, 'show'])->name('api.account.show');
                Route::put('/{id}', [AccountController::class, 'save'])->name('api.account.save');
//                Route::delete('/{id}', [AccountController::class, 'destroy'])->name('api.account.destroy');
            });
        });
    });
});
