<?php

use Illuminate\Support\Facades\Route;
use Workable\Navigation\Http\Controllers\Api\CategoryMultiController;
use Workable\Navigation\Http\Controllers\Api\NavigationController;

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

    Route::prefix('navigations')->group(function () {
        Route::get('/', [NavigationController::class, 'index'])->name('api.navigation.index');
        Route::post('/', [NavigationController::class, 'store'])->name('api.navigation.store');
        Route::get('/{id}', [NavigationController::class, 'show'])->name('api.navigation.show');
        Route::post('/{id}', [NavigationController::class, 'update'])->name('api.navigation.update');
        Route::delete('/{id}', [NavigationController::class, 'destroy'])->name('api.navigation.destroy');
    });
});
