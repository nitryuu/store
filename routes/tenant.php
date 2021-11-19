<?php

declare(strict_types=1);

use App\Http\Controllers\BranchController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
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
    Route::get('/', function () {
        return redirect('/login');
    });

    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'branchLogin']);
    Route::get('/logout', [LoginController::class, 'logout']);

    Route::middleware('auth:user')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // order
        Route::get('/order-list', [OrderController::class, 'index']);
        Route::get('/order-input', [OrderController::class, 'input']);
        Route::get('/order-details/{id}', [OrderController::class, 'show']);
        Route::post('/order', [OrderController::class, 'store']);
        Route::put('/order/{id}', [OrderController::class, 'update']);
        Route::delete('/order/{id}', [OrderController::class, 'destroy']);

        // profile
        Route::get('/profile', [ProfileController::class, 'index']);
        Route::post('/profile', [ProfileController::class, 'store']);

        // supplier
        Route::get('/supplier', [SupplierController::class, 'index']);
        Route::get('/supplier/{id}', [SupplierController::class, 'show']);
        Route::post('/supplier', [SupplierController::class, 'store']);
        Route::put('/supplier/{id}', [SupplierController::class, 'update']);
        Route::delete('/supplier/{id}', [SupplierController::class, 'destroy']);

        // category
        Route::get('/category', [CategoryController::class, 'index']);
        Route::get('/category/{id}', [CategoryController::class, 'show']);
        Route::post('/category', [CategoryController::class, 'store']);
        Route::put('/category/{id}', [CategoryController::class, 'update']);
        Route::delete('/category/{id}', [CategoryController::class, 'destroy']);

        // branch
        Route::get('/branch', [BranchController::class, 'index']);
        Route::get('/branch/{id}', [BranchController::class, 'show']);
        Route::post('/branch', [BranchController::class, 'store']);
        Route::put('/branch/{id}', [BranchController::class, 'update']);
        Route::delete('/branch/{id}', [BranchController::class, 'destroy']);

        // users
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/users/{id}', [UserController::class, 'show']);
        Route::post('/users', [UserController::class, 'store']);
        Route::put('/users/{id}', [UserController::class, 'update']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);
    });
});
