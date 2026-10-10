<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ExpenseCategoryController;
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
use App\Http\Controllers\TaxController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VariantController;
use App\Http\Controllers\WarehouseController;
use Illuminate\Support\Facades\Route;


Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('user', [AuthController::class, 'user']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
    });
});


Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('dashboard', [AppController::class, 'dashboard']);


    Route::apiResource('categories', CategoryController::class);
    Route::get('search/categories', [CategoryController::class, 'search']);

    Route::apiResource('brands', BrandController::class);
    Route::get('search/brands', [BrandController::class, 'search']);

    Route::apiResource('units', UnitController::class);
    Route::get('search/units', [UnitController::class, 'search']);

    Route::apiResource('taxes', TaxController::class);
    Route::get('search/taxes', [TaxController::class, 'search']);

    Route::apiResource('products', ProductController::class);
    Route::post('products/{product}/media', [ProductController::class, 'media']);

    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index']);
        Route::post('/items', [CartController::class, 'store']);
        Route::put('/items/{item}', [CartController::class, 'update']);
        Route::post('/items/{item}/increment', [CartController::class, 'increment']);
        Route::post('/items/{item}/decrement', [CartController::class, 'decrement']);
        Route::delete('/items/{item}', [CartController::class, 'destroy']);
        Route::delete('/', [CartController::class, 'clear']);
        Route::patch('/discount', [CartController::class, 'discount']);
        Route::patch('/shipping', [CartController::class, 'shipping']);
    });

    Route::prefix('purchases')->group(function () {
        Route::get('/', [PurchaseController::class, 'index']);
        Route::post('/', [PurchaseController::class, 'store']);
        Route::get('/{purchase}', [PurchaseController::class, 'show']);
        Route::put('/{purchase}', [PurchaseController::class, 'update']);
        Route::delete('/{purchase}', [PurchaseController::class, 'destroy']);

        Route::get('/recent', [PurchaseController::class, 'recent']);
        Route::get('/drafts', [PurchaseController::class, 'drafts']);

        Route::post('/{purchase}/receive', [PurchaseController::class, 'receive']);
        Route::post('/{purchase}/cancel', [PurchaseController::class, 'cancel']);
    });

    Route::prefix('sales')->group(function () {
        Route::get('/', [SaleController::class, 'index']);
        Route::post('/', [SaleController::class, 'store']);
        Route::get('/{sale}', [SaleController::class, 'show']);
        Route::put('/{sale}', [SaleController::class, 'update']);
        Route::delete('/{sale}', [SaleController::class, 'destroy']);

        Route::get('/drafts', [SaleController::class, 'drafts']);
        Route::get('/recent', [SaleController::class, 'recent']);
        Route::post('/hold', [SaleController::class, 'hold']);
        // Route::post('/{sale}/resume', [SaleController::class, 'resume']);
        // Route::post('/{sale}/complete', [SaleController::class, 'complete']);
        // Route::post('/{sale}/cancel', [SaleController::class, 'cancel']);
    });


    Route::apiResource('accounts', AccountController::class);
    Route::apiResource('transactions', TransactionController::class);
    Route::apiResource('payments', PaymentController::class);
    Route::apiResource('expenses', ExpenseController::class);

    Route::apiResource('variants', VariantController::class);
    Route::apiResource('warehouses', WarehouseController::class);


    Route::apiResource('suppliers', SupplierController::class);

    Route::prefix('customers')->group(function () {
        Route::get('search', [CustomerController::class, 'search']);
        Route::get('/', [CustomerController::class, 'index']);
        Route::post('/', [CustomerController::class, 'store']);
        Route::get('customers/{customer}', [CustomerController::class, 'show']);
        Route::put('customers/{customer}', [CustomerController::class, 'update']);
        Route::delete('customers/{customer}', [CustomerController::class, 'destroy']);
    });

    Route::apiResource('expense-categories', ExpenseCategoryController::class);
    Route::apiResource('expenses', ExpenseController::class);

    Route::prefix('reports')->group(function () {
        Route::get('accounts', [ReportController::class, 'index']);
        Route::get('accounts/summary', [ReportController::class, 'summary']);
        Route::get('accounts/transactions', [ReportController::class, 'transactions']);
    });

    Route::apiResource('users', UserController::class);

    Route::apiResource('stores', StoreController::class);

    Route::get('settings', [SettingController::class, 'index']);
    Route::put('settings', [SettingController::class, 'update']);

    Route::apiResource('logs', LogController::class)->only(['index', 'show']);
});
