<?php

use Illuminate\Support\Facades\Route;
use Workable\Contract\Http\Controllers\Api\CRMContractController;
use Workable\Contract\Http\Controllers\Api\TransactionController;


Route::group([
    'prefix'     => 'api/v1',
    'middleware' => config('acl.auth.middleware'),
], function () {
    //     feature
    Route::prefix('contracts')->group(function () {
        Route::get('/', [CRMContractController::class, 'index'])->name('api.contracts.index');
        Route::post('/', [CRMContractController::class, 'store'])->name('api.contracts.store');
        Route::get('/{id}', [CRMContractController::class, 'show'])->name('api.contracts.show');
        Route::post('/{id}', [CRMContractController::class, 'update'])->name('api.contracts.update');
        Route::delete('/{id}', [CRMContractController::class, 'destroy'])->name('api.contracts.destroy');
    });

    Route::prefix('transactions')->group(function () {
        Route::get('/', [TransactionController::class, 'index'])->name('api.transactions.index');
        Route::post('/', [TransactionController::class, 'store'])->name('api.transactions.store');
        Route::get('/{id}', [TransactionController::class, 'show'])->name('api.transactions.show');
        Route::post('/{id}', [TransactionController::class, 'update'])->name('api.transactions.update');
        Route::delete('/{id}', [TransactionController::class, 'destroy'])->name('api.transactions.destroy');
    });
});
