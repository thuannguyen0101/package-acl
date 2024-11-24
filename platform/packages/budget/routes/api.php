<?php

use Illuminate\Support\Facades\Route;
use Workable\Budget\Http\Controllers\Api\AccountMoneyController;
use Workable\Budget\Http\Controllers\Api\ExpenseCategoryController;

Route::group([
    'prefix'     => 'api/v1',
    'middleware' => config('acl.auth.middleware'),
], function () {
    // feature
    Route::prefix('account-moneys')->group(function () {
        Route::get('/', [AccountMoneyController::class, 'index'])->name('api.account_money.index');
        Route::post('/', [AccountMoneyController::class, 'store'])->name('api.account_money.store');
        Route::get('/{id}', [AccountMoneyController::class, 'show'])->name('api.account_money.show');
        Route::post('/{id}', [AccountMoneyController::class, 'update'])->name('api.account_money.update');
        Route::delete('/{id}', [AccountMoneyController::class, 'destroy'])->name('api.account_money.destroy');
    });
    Route::prefix('expense_categories')->group(function () {
        Route::get('/', [ExpenseCategoryController::class, 'index'])->name('api.expense_category.index');
        Route::post('/', [ExpenseCategoryController::class, 'store'])->name('api.expense_category.store');
        Route::get('/{id}', [ExpenseCategoryController::class, 'show'])->name('api.expense_category.show');
        Route::post('/{id}', [ExpenseCategoryController::class, 'update'])->name('api.expense_category.update');
        Route::delete('/{id}', [ExpenseCategoryController::class, 'destroy'])->name('api.expense_category.destroy');
    });
});
