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
use App\Http\Controllers\PurchaseReturnController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SaleReturnController;
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

    Route::prefix('categories')->group(function () {
        Route::get('/search', [CategoryController::class, 'search']);
        Route::get('/', [CategoryController::class, 'index']);
        Route::post('/', [CategoryController::class, 'store']);
        Route::get('/{category}', [CategoryController::class, 'show']);
        Route::put('/{category}', [CategoryController::class, 'update']);
        Route::delete('/{category}', [CategoryController::class, 'destroy']);
    });

    Route::prefix('brands')->group(function () {
        Route::get('/search', [BrandController::class, 'search']);
        Route::get('/', [BrandController::class, 'index']);
        Route::post('/', [BrandController::class, 'store']);
        Route::get('/{brand}', [BrandController::class, 'show']);
        Route::put('/{brand}', [BrandController::class, 'update']);
        Route::delete('/{brand}', [BrandController::class, 'destroy']);
    });

    Route::prefix('units')->group(function () {
        Route::get('/search', [UnitController::class, 'search']);
        Route::get('/', [UnitController::class, 'index']);
        Route::post('/', [UnitController::class, 'store']);
        Route::get('/{unit}', [UnitController::class, 'show']);
        Route::put('/{unit}', [UnitController::class, 'update']);
        Route::delete('/{unit}', [UnitController::class, 'destroy']);
    });


    Route::prefix('taxes')->group(function () {
        Route::get('/search', [TaxController::class, 'search']);
        Route::get('/', [TaxController::class, 'index']);
        Route::post('/', [TaxController::class, 'store']);
        Route::get('/{tax}', [TaxController::class, 'show']);
        Route::put('/{tax}', [TaxController::class, 'update']);
        Route::delete('/{tax}', [TaxController::class, 'destroy']);
    });


    Route::prefix('products')->group(function () {
        Route::get('/search', [ProductController::class, 'search']);
        Route::get('/', [ProductController::class, 'index']);
        Route::post('/', [ProductController::class, 'store']);
        Route::get('/{product}', [ProductController::class, 'show']);
        Route::put('/{product}', [ProductController::class, 'update']);
        Route::delete('/{product}', [ProductController::class, 'destroy']);
        Route::post('{product}/media', [ProductController::class, 'media']);
    });


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
        Route::prefix('returns')->group(function () {
            Route::get('/', [PurchaseReturnController::class, 'index']);
            Route::post('/', [PurchaseReturnController::class, 'store']);
            Route::get('/{purchaseReturn}', [PurchaseReturnController::class, 'show']);
            Route::put('/{purchaseReturn}', [PurchaseReturnController::class, 'update']);
            Route::delete('/{purchaseReturn}', [PurchaseReturnController::class, 'destroy']);
        });

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
        Route::prefix('returns')->group(function () {
            Route::get('/', [SaleReturnController::class, 'index']);
            Route::post('/', [SaleReturnController::class, 'store']);
            Route::get('/{saleReturn}', [SaleReturnController::class, 'show']);
            Route::put('/{saleReturn}', [SaleReturnController::class, 'update']);
            Route::delete('/{saleReturn}', [SaleReturnController::class, 'destroy']);
        });


        Route::get('/drafts', [SaleController::class, 'drafts']);
        Route::get('/recent', [SaleController::class, 'recent']);
        Route::post('/hold', [SaleController::class, 'hold']);

        Route::get('/', [SaleController::class, 'index']);
        Route::post('/', [SaleController::class, 'store']);
        Route::get('/{sale}', [SaleController::class, 'show']);
        Route::put('/{sale}', [SaleController::class, 'update']);
        Route::delete('/{sale}', [SaleController::class, 'destroy']);
    });


    Route::apiResource('accounts', AccountController::class);
    Route::apiResource('transactions', TransactionController::class);
    Route::apiResource('payments', PaymentController::class);
    Route::apiResource('expenses', ExpenseController::class);

    Route::apiResource('variants', VariantController::class);
    Route::apiResource('warehouses', WarehouseController::class);

    Route::prefix('suppliers')->group(function () {
        Route::get('/search', [SupplierController::class, 'search']);
        Route::get('/', [SupplierController::class, 'index']);
        Route::post('/', [SupplierController::class, 'store']);
        Route::get('/{supplier}', [SupplierController::class, 'show']);
        Route::put('/{supplier}', [SupplierController::class, 'update']);
        Route::delete('/{supplier}', [SupplierController::class, 'destroy']);
    });


    Route::prefix('stores')->group(function () {
        Route::get('/search', [StoreController::class, 'search']);
        Route::get('/', [StoreController::class, 'index']);
        Route::post('/', [StoreController::class, 'store']);
        Route::get('/{store}', [StoreController::class, 'show']);
        Route::put('/{store}', [StoreController::class, 'update']);
        Route::delete('/{store}', [StoreController::class, 'destroy']);
    });


    Route::prefix('customers')->group(function () {
        Route::get('search', [CustomerController::class, 'search']);
        Route::get('/', [CustomerController::class, 'index']);
        Route::post('/', [CustomerController::class, 'store']);
        Route::get('{customer}', [CustomerController::class, 'show']);
        Route::put('{customer}', [CustomerController::class, 'update']);
        Route::delete('{customer}', [CustomerController::class, 'destroy']);

        Route::get('{customer}/sales', [CustomerController::class, 'sales']);
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

Route::get('reset', [SettingController::class, 'reset']);
Route::get('seed', [SettingController::class, 'seed']);
