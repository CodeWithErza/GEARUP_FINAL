<?php
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StockinController;
use App\Http\Controllers\StockAdjustmentController;
use App\Http\Controllers\StockoutController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // User profile API route
    Route::get('/api/user/profile', function () {
        return auth()->user();
    });
    
    // User Routes
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::patch('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    
    // Inventory Management Routes
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory');
    
    // Stockin Routes
    Route::get('/inventory/stock-in', [StockinController::class, 'index'])->name('inventory.stock-in');
    Route::post('/inventory/stock-in', [StockinController::class, 'store'])->name('inventory.stock-in.store');
    Route::get('/inventory/stock-in/{stockin}', [StockinController::class, 'show'])->name('inventory.stock-in.show');
    Route::delete('/inventory/stock-in/{stockin}', [StockinController::class, 'destroy'])->name('inventory.stock-in.destroy');
    Route::get('/inventory/stock-in/suppliers', [StockinController::class, 'getSuppliers'])->name('inventory.stock-in.suppliers');
    Route::get('/inventory/stock-in/products', [StockinController::class, 'getProducts'])->name('inventory.stock-in.products');
    
    Route::get('/inventory/stock-out', [StockoutController::class, 'index'])->name('inventory.stock-out');
    Route::post('/inventory/stock-out', [StockoutController::class, 'store'])->name('stockout.store');
    
    // Supplier Routes
    Route::get('/suppliers/search', [SupplierController::class, 'search'])->name('suppliers.search');
    Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
    Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
    Route::get('/suppliers/{supplier}', [SupplierController::class, 'show'])->name('suppliers.show');
    Route::put('/suppliers/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update');
    Route::delete('/suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');

    // Product Routes
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::put('/products/{product}/toggle-active', [ProductController::class, 'toggleActive'])->name('products.toggle-active');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::post('/products/{id}/restore', [ProductController::class, 'restore'])->name('products.restore');
    Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');
    Route::get('/products/low-stock', [ProductController::class, 'lowStock'])->name('products.low-stock');
    Route::post('/products/{product}/stock', [ProductController::class, 'updateStock'])->name('products.update-stock');

    // API Routes for the application
    Route::prefix('api')->group(function() {
        Route::get('/products', [ProductController::class, 'apiIndex']);
        Route::get('/products/search', [ProductController::class, 'apiSearch']);
        Route::get('/customers', [CustomerController::class, 'index']);
        Route::get('/customers/search', [CustomerController::class, 'search']);
    });

    // Order routes
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/history', [OrderController::class, 'history'])->name('orders.history');
    Route::get('/orders/history/data', [OrderController::class, 'getOrderHistory'])->name('orders.history.data');
    Route::get('/orders/dashboard/data', [OrderController::class, 'getDashboardData'])->name('orders.dashboard.data');
    Route::get('/orders/history/export/{format}', [OrderController::class, 'exportHistory'])->name('orders.history.export');
    Route::get('/orders/{order}/details', [OrderController::class, 'getOrderDetails'])->name('orders.details');
    Route::get('/orders/products', [OrderController::class, 'getProducts'])->name('orders.products');
    Route::get('/orders/search-barcode', [OrderController::class, 'searchBarcode'])->name('orders.search-barcode');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}/receipt', [OrderController::class, 'printReceipt'])->name('orders.receipt');
    Route::get('/orders/{order}/edit', [OrderController::class, 'edit'])->name('orders.edit');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::put('/orders/{order}', [OrderController::class, 'update'])->name('orders.update');

    // Stock Adjustments Routes
    Route::post('/stock-adjustments', [StockAdjustmentController::class, 'store'])->name('stock-adjustments.store');
    Route::get('/stock-adjustments', [StockAdjustmentController::class, 'index'])->name('stock-adjustments.index');
    Route::get('/stock-adjustments/{stockAdjustment}', [StockAdjustmentController::class, 'show'])->name('stock-adjustments.show');

    // Report Routes
    Route::prefix('reports')->group(function() {
        Route::get('/sales', [ReportController::class, 'sales'])->name('reports.sales');
        Route::get('/sales-by-category', [ReportController::class, 'getSalesByCategory'])->name('reports.sales-by-category');
        Route::get('/recent-sales', [ReportController::class, 'getRecentSales'])->name('reports.recent-sales');
        Route::get('/sales-details', [ReportController::class, 'getSalesDetails'])->name('reports.sales-details');
        Route::get('/sales-summary', [ReportController::class, 'getSalesSummary'])->name('reports.sales-summary');
        Route::get('/sales-trend', [ReportController::class, 'getSalesTrend'])->name('reports.sales-trend');
        Route::get('/top-selling-products', [ReportController::class, 'getTopSellingProducts'])->name('reports.top-selling-products');
        Route::get('/export/pdf', [ReportController::class, 'exportOrders'])->name('reports.export.pdf')->defaults('format', 'pdf');
        Route::get('/export/excel', [ReportController::class, 'exportOrders'])->name('reports.export.excel')->defaults('format', 'excel');
    });
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

require __DIR__.'/auth.php';
