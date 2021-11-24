<?php

declare(strict_types=1);

use App\Http\Controllers\BranchController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\InitializeTenancyByPath;
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

// Route::middleware([
//     'web',
//     InitializeTenancyByDomain::class,
//     PreventAccessFromCentralDomains::class,
// ])->group(function () {
Route::group([
    'as' => 'tenant.',
    'prefix' => '/{tenant?}',
    'middleware' => ['web', InitializeTenancyByPath::class]
], function () {
    Route::get('/', function () {
        return redirect('/login');
    });

    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'branchLogin'])->name('login.post');
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::middleware('auth:user')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/chart', [DashboardController::class, 'chart']);
        Route::post('/chart', [DashboardController::class, 'filter_chart']);

        // order
        Route::get('/order-list', [OrderController::class, 'index']);
        Route::get('/order-input', [OrderController::class, 'input'])->name('order-input');
        Route::get('/order-details/{id}', [OrderController::class, 'show']);
        Route::post('/order', [OrderController::class, 'store'])->name('order');
        Route::put('/order/{id}', [OrderController::class, 'update']);
        Route::delete('/order/{id}', [OrderController::class, 'destroy']);

        // profile
        Route::get('/profile', [ProfileController::class, 'index']);
        Route::post('/profile', [ProfileController::class, 'store'])->name('profile.post');

        // supplier
        Route::get('/supplier', [SupplierController::class, 'index'])->name('supplier');
        Route::get('/supplier/{id}', [SupplierController::class, 'show']);
        Route::post('/supplier', [SupplierController::class, 'store'])->name('supplier.post');
        Route::put('/supplier/{id}', [SupplierController::class, 'update'])->name('supplier.put');
        Route::delete('/supplier/{id}', [SupplierController::class, 'destroy']);

        // category
        Route::get('/category', [CategoryController::class, 'index'])->name('category');
        Route::get('/category/{id}', [CategoryController::class, 'show']);
        Route::post('/category', [CategoryController::class, 'store'])->name('category.post');
        Route::put('/category/{id}', [CategoryController::class, 'update'])->name('category.put');
        Route::delete('/category/{id}', [CategoryController::class, 'destroy']);

        // income
        Route::get('/income', [IncomeController::class, 'index'])->name('income');
        Route::post('/income', [IncomeController::class, 'store'])->name('income.post');
        Route::get('/income/{id}', [IncomeController::class, 'show']);
        Route::put('/income/{id}', [IncomeController::class, 'update']);

        // branch
        Route::get('/branch', [BranchController::class, 'index'])->name('branch');
        Route::get('/branch/{id}', [BranchController::class, 'show']);
        Route::post('/branch', [BranchController::class, 'store'])->name('branch.post');
        Route::put('/branch/{id}', [BranchController::class, 'update'])->name('branch.put');
        Route::delete('/branch/{id}', [BranchController::class, 'destroy']);

        // users
        Route::get('/users', [UserController::class, 'index'])->name('users');
        Route::get('/users/{id}', [UserController::class, 'show']);
        Route::post('/users', [UserController::class, 'store'])->name('users.post');
        Route::put('/users/{id}', [UserController::class, 'update']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);
    });
});
