<?php

use Illuminate\Support\Facades\Route;
use Workable\Budget\Http\Controllers\Api\AccountMoneyController;

Route::group([
    'prefix'     => 'api/v1',
    'middleware' => config('acl.auth.middleware'),
], function () {
    // feature
    Route::prefix('account-money')->group(function () {
        Route::get('/', [AccountMoneyController::class, 'index'])->name('api.account_money.index');
        Route::post('/', [AccountMoneyController::class, 'store'])->name('api.account_money.store');
        Route::get('/{id}', [AccountMoneyController::class, 'show'])->name('api.account_money.show');
        Route::post('/{id}', [AccountMoneyController::class, 'update'])->name('api.account_money.update');
        Route::delete('/{id}', [AccountMoneyController::class, 'destroy'])->name('api.account_money.destroy');
    });
});
