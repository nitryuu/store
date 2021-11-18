<?php

declare(strict_types=1);

use App\Http\Controllers\BranchController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'branchLogin']);
    Route::get('/logout', [LoginController::class, 'logout']);

    Route::middleware('auth:user')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/order-list', [OrderController::class, 'index']);
        Route::get('/order-input', [OrderController::class, 'input']);
        Route::post('/order', [OrderController::class, 'store']);

        // supplier
        Route::get('/supplier', [SupplierController::class, 'index']);
        Route::post('/supplier', [SupplierController::class, 'store']);

        // category
        Route::get('/category', [CategoryController::class, 'index']);
        Route::post('/category', [CategoryController::class, 'store']);

        // branch
        Route::get('/branch', [BranchController::class, 'index']);
        Route::post('/branch', [BranchController::class, 'store']);
    });
});
