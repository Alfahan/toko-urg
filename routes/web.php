<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Home
Route::get('/', function () {
    return \Inertia\Inertia::render('Auth/Login');
})->middleware('guest');

// prefix "apps"
Route::prefix('apps')->group(function() {
    // middleware "auth"
    Route::group(['middleware' => ['auth']], function () {
        // route dashboard
        Route::get('dashboard', App\Http\Controllers\Apps\DashboardController::class)->name('apps.dashboard');

        // route permissions
        Route::get('/permissions', \App\Http\Controllers\Apps\PermissionController::class)->name('apps.permissions.index')->middleware('permission:permissions.index');

        //route resource roles
        Route::resource('/roles', \App\Http\Controllers\Apps\RoleController::class, ['as' => 'apps'])
            ->middleware('permission:roles.index|roles.create|roles.edit|roles.delete');

        //route resource users
        Route::resource('/users', \App\Http\Controllers\Apps\UserController::class, ['as' => 'apps'])
            ->middleware('permission:users.index|users.create|users.edit|users.delete');

        //route resource unit of measurent
        Route::resource('/unit_of_measurement', \App\Http\Controllers\Apps\UnitOfMeasurementsController::class, ['as' => 'apps'])
        ->middleware('permission:unit_of_measurement.index|unit_of_measurement.create|unit_of_measurement.edit|unit_of_measurement.delete');

        // route product import
        Route::get('/products/import', [\App\Http\Controllers\Apps\ProductController::class, 'import'])->name('apps.products.import');

        // route product store import
        Route::post('/products/import', [\App\Http\Controllers\Apps\ProductController::class, 'storeImport'])->name('apps.products.storeImport');

        //route resource products
        Route::resource('/products', \App\Http\Controllers\Apps\ProductController::class, ['as' => 'apps'])
        ->middleware('permission:products.index|products.create|products.edit|products.delete');

        //route report export excel
        Route::get('/product_report/export', [\App\Http\Controllers\Apps\ProductReportController::class, 'export'])->name('apps.product_report.export');

        //route report print pdf
        Route::get('/product_report/pdf', [\App\Http\Controllers\Apps\ProductReportController::class, 'pdf'])->name('apps.product_report.pdf');

        //route resource suppliers
        Route::resource('/suppliers', \App\Http\Controllers\Apps\SupplierController::class, ['as' => 'apps'])
        ->middleware('permission:suppliers.index|suppliers.create|suppliers.edit|suppliers.delete');

        //route report export excel
        Route::get('/suppliers_report/export', [\App\Http\Controllers\Apps\SupplierReportController::class, 'export'])->name('apps.suppliers_report.export');

        //route report print pdf
        Route::get('/suppliers_report/pdf', [\App\Http\Controllers\Apps\SupplierReportController::class, 'pdf'])->name('apps.suppliers_report.pdf');

        //route resource customers
        Route::resource('/customers', \App\Http\Controllers\Apps\CustomerController::class, ['as' => 'apps'])
            ->middleware('permission:customers.index|customers.create|customers.edit|customers.delete');

        //route report export excel
        Route::get('/customers_report/export', [\App\Http\Controllers\Apps\CustomerReportController::class, 'export'])->name('apps.customers_report.export');

        //route report print pdf
        Route::get('/customers_report/pdf', [\App\Http\Controllers\Apps\CustomerReportController::class, 'pdf'])->name('apps.customers_report.pdf');

        // PURCHASE
        //route transaction
        Route::get('/purchase_transactions', [\App\Http\Controllers\Apps\PurchaseTransactionController::class, 'index'])->name('apps.purchase_transactions.index');

        //route transaction searchProduct
        Route::post('/purchase_transactions/searchProduct', [\App\Http\Controllers\Apps\PurchaseTransactionController::class, 'searchProduct'])->name('apps.purchase_transactions.searchProduct');

        //route transaction addToCart
        Route::post('/purchase_transactions/addToCart', [\App\Http\Controllers\Apps\PurchaseTransactionController::class, 'addToCart'])->name('apps.purchase_transactions.addToCart');

        //route transaction destroyCart
        Route::post('/purchase_transactions/destroyCart', [\App\Http\Controllers\Apps\PurchaseTransactionController::class, 'destroyCart'])->name('apps.purchase_transactions.destroyCart');

        //route transaction store
        Route::post('/purchase_transactions/store', [\App\Http\Controllers\Apps\PurchaseTransactionController::class, 'store'])->name('apps.purchase_transactions.store');

        //route transaction print
        Route::get('/purchase_transactions/print', [\App\Http\Controllers\Apps\PurchaseTransactionController::class, 'print'])->name('apps.purchase_transactions.print');

        //route report index
        Route::get('/purchase_report', [\App\Http\Controllers\Apps\PurchaseReportController::class, 'index'])->middleware('permission:purchase_report.index')->name('apps.purchase_report.index');

        // route report detail
        Route::get('/purchase_report/detail_purchase/{invoice}', [\App\Http\Controllers\Apps\PurchaseReportController::class, 'detail_invoice'])->name('apps.purchase_report.detail_invoice');

        //route report filter
        Route::get('/purchase_report/filter', [\App\Http\Controllers\Apps\PurchaseReportController::class, 'filter'])->name('apps.purchase_report.filter');

        //route report invoice purchase
        Route::get('/purchase_report/search', [\App\Http\Controllers\Apps\PurchaseReportController::class, 'search'])->name('apps.purchase_report.search');

        //route report export excel
        Route::get('/purchase_report/export', [\App\Http\Controllers\Apps\PurchaseReportController::class, 'export'])->name('apps.purchase_report.export');

        // //route report print pdf
        Route::get('/purchase_report/pdf', [\App\Http\Controllers\Apps\PurchaseReportController::class, 'pdf'])->name('apps.purchase_report.pdf');

        // COST
        //route transaction
        Route::get('/cost_transactions', [\App\Http\Controllers\Apps\CostOperationalController::class, 'index'])->name('apps.cost_transactions.index');

        //route transaction addToCart
        Route::post('/cost_transactions/addToCart', [\App\Http\Controllers\Apps\CostOperationalController::class, 'addToCart'])->name('apps.cost_transactions.addToCart');

        //route transaction destroyCart
        Route::post('/cost_transactions/destroyCart', [\App\Http\Controllers\Apps\CostOperationalController::class, 'destroyCart'])->name('apps.cost_transactions.destroyCart');

        //route transaction store
        Route::post('/cost_transactions/store', [\App\Http\Controllers\Apps\CostOperationalController::class, 'store'])->name('apps.cost_transactions.store');

        //route transaction print
        Route::get('/cost_transactions/print', [\App\Http\Controllers\Apps\CostOperationalController::class, 'print'])->name('apps.cost_transactions.print');

        //route report index
        Route::get('/cost_report', [\App\Http\Controllers\Apps\CostReportController::class, 'index'])->middleware('permission:cost_report.index')->name('apps.cost_report.index');

        // route report detail
        Route::get('/cost_report/detail_cost/{invoice}', [\App\Http\Controllers\Apps\CostReportController::class, 'detail_invoice'])->name('apps.cost_report.detail_invoice');

        //route report filter
        Route::get('/cost_report/filter', [\App\Http\Controllers\Apps\CostReportController::class, 'filter'])->name('apps.cost_report.filter');

        //route report invoice cost
        Route::get('/cost_report/search', [\App\Http\Controllers\Apps\CostReportController::class, 'search'])->name('apps.cost_report.search');

        //route report export excel
        Route::get('/cost_report/export', [\App\Http\Controllers\Apps\CostReportController::class, 'export'])->name('apps.cost_report.export');

        //route report print pdf
        Route::get('/cost_report/pdf', [\App\Http\Controllers\Apps\CostReportController::class, 'pdf'])->name('apps.cost_report.pdf');

        // TRANSACTION

        //route transaction
        Route::get('/transactions', [\App\Http\Controllers\Apps\TransactionController::class, 'index'])->name('apps.transactions.index');

        //route transaction searchProduct
        Route::post('/transactions/searchProduct', [\App\Http\Controllers\Apps\TransactionController::class, 'searchProduct'])->name('apps.transactions.searchProduct');

        //route transaction addToCart
        Route::post('/transactions/addToCart', [\App\Http\Controllers\Apps\TransactionController::class, 'addToCart'])->name('apps.transactions.addToCart');

        //route transaction destroyCart
        Route::post('/transactions/destroyCart', [\App\Http\Controllers\Apps\TransactionController::class, 'destroyCart'])->name('apps.transactions.destroyCart');

        //route transaction store
        Route::post('/transactions/store', [\App\Http\Controllers\Apps\TransactionController::class, 'store'])->name('apps.transactions.store');

        //route transaction print
        Route::get('/transactions/print', [\App\Http\Controllers\Apps\TransactionController::class, 'print'])->name('apps.transactions.print');

        //route sales index
        Route::get('/sales', [\App\Http\Controllers\Apps\SaleController::class, 'index'])->middleware('permission:sales.index')->name('apps.sales.index');

        // route sales detail
        Route::get('/sales/detail_sales/{invoice}', [\App\Http\Controllers\Apps\SaleController::class, 'detail_invoice'])->name('apps.sales.detail_invoice');

        //route sales filter
        Route::get('/sales/filter', [\App\Http\Controllers\Apps\SaleController::class, 'filter'])->name('apps.sales.filter');

        //route sales export excel
        Route::get('/sales/export', [\App\Http\Controllers\Apps\SaleController::class, 'export'])->name('apps.sales.export');

        // //route sales print pdf
        Route::get('/sales/pdf', [\App\Http\Controllers\Apps\SaleController::class, 'pdf'])->name('apps.sales.pdf');

        //route profits index
        Route::get('/profits', [\App\Http\Controllers\Apps\ProfitController::class, 'index'])->middleware('permission:profits.index')->name('apps.profits.index');

        // //route profits filter
        Route::get('/profits/filter', [\App\Http\Controllers\Apps\ProfitController::class, 'filter'])->name('apps.profits.filter');

        // //route profits export
        Route::get('/profits/export', [\App\Http\Controllers\Apps\ProfitController::class, 'export'])->name('apps.profits.export');

        // //route profits pdf
        Route::get('/profits/pdf', [\App\Http\Controllers\Apps\ProfitController::class, 'pdf'])->name('apps.profits.pdf');

    });
});

