<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AccountingController;
use App\Http\Controllers\AdjustmentController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarcodeController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\LogController;
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
use App\Http\Controllers\TransferController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
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
        Route::get('barcode', [ProductController::class, 'byBarcode']);
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


    Route::prefix('suppliers')->group(function () {
        Route::get('/search', [SupplierController::class, 'search']);
        Route::get('/', [SupplierController::class, 'index']);
        Route::post('/', [SupplierController::class, 'store']);
        Route::get('/{supplier}', [SupplierController::class, 'show']);
        Route::put('/{supplier}', [SupplierController::class, 'update']);
        Route::delete('/{supplier}', [SupplierController::class, 'destroy']);
        Route::patch('/{supplier}/enable', [SupplierController::class, 'enable']);
        Route::patch('/{supplier}/disable', [SupplierController::class, 'disable']);
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

    Route::prefix('stores')->group(function () {
        Route::get('/search', [StoreController::class, 'search']);
        Route::get('/', [StoreController::class, 'index']);
        Route::post('/', [StoreController::class, 'store']);
        Route::get('/{store}', [StoreController::class, 'show']);
        Route::put('/{store}', [StoreController::class, 'update']);
        Route::delete('/{store}', [StoreController::class, 'destroy']);
    });

    Route::prefix('expenses')->group(function () {
        Route::prefix('categories')->group(function () {
            Route::get('/search', [ExpenseCategoryController::class, 'search']);
            Route::get('/', [ExpenseCategoryController::class, 'index']);
            Route::post('/', [ExpenseCategoryController::class, 'store']);
            Route::get('/{expenseCategory}', [ExpenseCategoryController::class, 'show']);
            Route::put('/{expenseCategory}', [ExpenseCategoryController::class, 'update']);
            Route::delete('/{expenseCategory}', [ExpenseCategoryController::class, 'destroy']);
        });

        Route::get('/', [ExpenseController::class, 'index']);
        Route::post('/', [ExpenseController::class, 'store']);
        Route::get('/{expense}', [ExpenseController::class, 'show']);
        Route::put('/{expense}', [ExpenseController::class, 'update']);
        Route::delete('/{expense}', [ExpenseController::class, 'destroy']);
    });

    Route::prefix('inventory')->group(function () {

        // Low Stock
        Route::get('low-stock', [InventoryController::class, 'lowStock']);

        // Stock Adjustments
        Route::get('adjustments', [AdjustmentController::class, 'index']);
        Route::post('adjustments', [AdjustmentController::class, 'store']);
        Route::get('adjustments/{adjustment}', [AdjustmentController::class, 'show']);
        Route::put('adjustments/{adjustment}', [AdjustmentController::class, 'update']);
        Route::delete('adjustments/{adjustment}', [AdjustmentController::class, 'destroy']);

        // Stock Transfers
        Route::get('transfers', [TransferController::class, 'index']);
        Route::post('transfers', [TransferController::class, 'store']);
        Route::get('transfers/{transfer}', [TransferController::class, 'show']);
        Route::put('transfers/{transfer}', [TransferController::class, 'update']);
        Route::delete('transfers/{transfer}', [TransferController::class, 'destroy']);

        // Units
        Route::get('units', [UnitController::class, 'index']);
        Route::post('units', [UnitController::class, 'store']);
        Route::get('units/{unit}', [UnitController::class, 'show']);
        Route::put('units/{unit}', [UnitController::class, 'update']);
        Route::delete('units/{unit}', [UnitController::class, 'destroy']);

        // barcodes
        Route::get('barcodes', [BarcodeController::class, 'index']);
        Route::post('barcodes', [BarcodeController::class, 'store']);
        Route::get('barcodes/{product}', [BarcodeController::class, 'show']);
        Route::put('barcodes/{product}', [BarcodeController::class, 'update']);
        Route::delete('barcodes/{product}', [BarcodeController::class, 'destroy']);
    });

    Route::prefix('accounting')->group(function () {
        Route::get('/', [AccountingController::class, 'overview']);

        Route::prefix('accounts')->group(function () {
            Route::get('/search', [AccountController::class, 'search']);
            Route::get('/', [AccountController::class, 'index']);
            Route::post('/', [AccountController::class, 'store']);
            Route::get('/{account}', [AccountController::class, 'show']);
            Route::put('/{account}', [AccountController::class, 'update']);
            Route::delete('/{account}', [AccountController::class, 'destroy']);
        });

        Route::get('transactions', [AccountingController::class, 'transactions']);

        Route::get('cash-flow', [AccountingController::class, 'cashFlow']);
        Route::get('profit-loss', [AccountingController::class, 'profitLoss']);
        Route::get('balance-sheet', [AccountingController::class, 'balanceSheet']);
    });

    Route::prefix('reports')->group(function () {
        Route::get('sales', [ReportController::class, 'sales']);
        Route::get('purchases', [ReportController::class, 'purchases']);
        Route::get('inventory', [ReportController::class, 'inventory']);
        Route::get('customers', [ReportController::class, 'customers']);
        Route::get('suppliers', [ReportController::class, 'suppliers']);
        Route::get('expenses', [ReportController::class, 'expenses']);
    });

    Route::prefix('users')->group(function () {
        Route::get('search', [UserController::class, 'search']);
        Route::get('/', [UserController::class, 'index']);
        Route::post('/', [UserController::class, 'store']);
        Route::get('{user}', [UserController::class, 'show']);
        Route::put('{user}', [UserController::class, 'update']);
        Route::delete('{user}', [UserController::class, 'destroy']);
    });


    Route::get('settings', [SettingController::class, 'index']);
    Route::put('settings', [SettingController::class, 'update']);

    Route::apiResource('logs', LogController::class)->only(['index', 'show']);
});

Route::get('reset', [SettingController::class, 'reset']);
Route::get('seed', [SettingController::class, 'seed']);
