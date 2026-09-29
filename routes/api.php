<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VariantController;
use App\Http\Controllers\WarehouseController;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')->group(function () {

    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthController::class, 'login']);
        Route::post('register', [AuthController::class, 'register']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::get('user', [AuthController::class, 'user']);
            Route::post('logout', [AuthController::class, 'logout']);
            Route::post('refresh', [AuthController::class, 'refresh']);
        });
    });


    Route::middleware(['auth:sanctum','store'])->group(function () {
        Route::apiResource('users', UserController::class);

        Route::apiResource('stores', StoreController::class);

        Route::get('settings', [SettingController::class, 'index']);
        Route::put('settings', [SettingController::class, 'update']);

        Route::apiResource('logs', LogController::class)->only(['index', 'show']);

        Route::apiResource('accounts', AccountController::class);
        Route::apiResource('transactions', TransactionController::class);
        Route::apiResource('payments', PaymentController::class);
        Route::apiResource('expenses', ExpenseController::class);

        Route::apiResource('sales', SaleController::class);
        Route::apiResource('purchases', PurchaseController::class);



        Route::apiResource('products', ProductController::class);
        Route::apiResource('categories', CategoryController::class);
        Route::apiResource('brands', BrandController::class);
        Route::apiResource('units', UnitController::class);
        Route::apiResource('variants', VariantController::class);
        Route::apiResource('warehouses', WarehouseController::class);

        Route::apiResource('suppliers', SupplierController::class);


        Route::prefix('reports')->group(function () {
            Route::get('accounts', [ReportController::class, 'index']);
            Route::get('accounts/summary', [ReportController::class, 'summary']);
            Route::get('accounts/transactions', [ReportController::class, 'transactions']);
        });
    });
});
