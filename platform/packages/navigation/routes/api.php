<?php

use Illuminate\Support\Facades\Route;
use Workable\Navigation\Http\Controllers\Api\CategoryMultiController;

Route::group([
    'prefix'     => 'api/v1',
    'middleware' => config('acl.auth.middleware'),
], function () {
    // feature
    Route::prefix('category-multi')->group(function () {
        Route::get('/', [CategoryMultiController::class, 'index'])->name('api.category_multi.index');
        Route::post('/', [CategoryMultiController::class, 'store'])->name('api.category_multi.store');
        Route::get('/{id}', [CategoryMultiController::class, 'show'])->name('api.category_multi.show');
        Route::post('/{id}', [CategoryMultiController::class, 'update'])->name('api.category_multi.update');
        Route::delete('/{id}', [CategoryMultiController::class, 'destroy'])->name('api.category_multi.destroy');
    });
});
