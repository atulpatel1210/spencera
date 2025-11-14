<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PartyController; 
use App\Http\Controllers\DesignController;
use App\Http\Controllers\FinishController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\PalletController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\PurchaseOrderPalletController;
use App\Http\Controllers\DispatchController;
use App\Http\Controllers\StockPalletController;

Route::get('/', function () {
    return view('welcome');
});

// Dashboard
Route::get('/dashboard', [HomeController::class, 'index'])->middleware('auth')->name('dashboard');

Route::get('parties/data', [PartyController::class, 'getPartiesData'])->name('parties.data');
Route::get('parties/download-sample', [PartyController::class, 'downloadSampleFile'])->name('parties.download-sample');
Route::get('parties/import', [PartyController::class, 'showImportForm'])->name('parties.import.form');
Route::post('parties/import', [PartyController::class, 'import'])->name('parties.import');
Route::resource('parties', PartyController::class);

Route::get('designs/data', [DesignController::class, 'getDesignsData'])->name('designs.data');
Route::get('designs/download-sample', [DesignController::class, 'downloadSampleFile'])->name('designs.download-sample');
Route::get('designs/import', [DesignController::class, 'showImportForm'])->name('designs.import.form');
Route::post('designs/import', [DesignController::class, 'import'])->name('designs.import');
Route::get('/party/designs', [DesignController::class, 'getDesignByParty'])->name('party.designs');
Route::resource('designs', DesignController::class)->middleware('auth');

Route::get('finishes/data', [FinishController::class, 'getFinishesData'])->name('finishes.data');
Route::get('finishes/download-sample', [FinishController::class, 'downloadSampleFile'])->name('finishes.download-sample');
Route::get('finishes/import', [FinishController::class, 'showImportForm'])->name('finishes.import.form');
Route::post('finishes/import', [FinishController::class, 'import'])->name('finishes.import');
Route::resource('finishes', FinishController::class)->middleware('auth');

Route::get('sizes/data', [SizeController::class, 'getSizesData'])->name('sizes.data');
Route::get('sizes/download-sample', [SizeController::class, 'downloadSampleFile'])->name('sizes.download-sample');
Route::get('sizes/import', [SizeController::class, 'showImportForm'])->name('sizes.import.form');
Route::post('sizes/import', [SizeController::class, 'import'])->name('sizes.import');
Route::resource('sizes', SizeController::class)->middleware('auth');

Route::resource('pallet', PalletController::class)->middleware('auth');
Route::get('orders/order-item-list', [PurchaseOrderController::class, 'getAllItem'])->name('purchase_order_item.list');
// Route::get('orders/order-item-list', [PurchaseOrderController::class, 'getAllItem'])->name('purchase_order_item.list');
Route::get('orders/order-item-data', [PurchaseOrderController::class, 'getOrderItemData'])->name('purchase_order_item.data');
Route::get('orders/data', [PurchaseOrderController::class, 'getOrdersData'])->name('orders.data');
Route::resource('orders', PurchaseOrderController::class)->middleware('auth');

Route::get('orders/order-item-data/{id}', [PurchaseOrderController::class, 'getItem']);
Route::patch('orders/update-order-item/{id}', [PurchaseOrderController::class, 'updateOrderItem']);
Route::get('/purchase-order-pallets/index', [PurchaseOrderPalletController::class, 'index'])->name('purchase_order_pallets.index');
Route::get('purchase-order-pallets/data', [PurchaseOrderPalletController::class, 'getPalletsData'])->name('purchase_order_pallets.data');
Route::get('/purchase-order-pallets/create', [PurchaseOrderPalletController::class, 'create'])->name('purchase_order_pallets.create');
Route::post('/purchase-order-pallets', [PurchaseOrderPalletController::class, 'store'])->name('purchase_order_pallets.store');
Route::get('/get-order', [PurchaseOrderPalletController::class, 'getOrder']);
Route::get('/get-order-items', [PurchaseOrderPalletController::class, 'getOrderItems']);
Route::get('/get-batches', [PurchaseOrderPalletController::class, 'getBatches']);

Route::post('get-purchases-for-dispatch', [DispatchController::class, 'getPurchasesForDispatch']);
Route::post('get-designs-for-dispatch', [DispatchController::class, 'getDesignsForDispatch']);
Route::post('get-sizes-for-dispatch', [DispatchController::class, 'getSizesForDispatch']);
Route::post('get-finishs-for-dispatch', [DispatchController::class, 'getFinishsForDispatch']);
Route::post('get-order-items-for-dispatch', [DispatchController::class, 'getOrderItemsForDispatch']);
Route::post('get-batches-for-dispatch', [DispatchController::class, 'getBatchesForDispatch']);
Route::post('get-pallets-for-dispatch', [DispatchController::class, 'getPalletsForDispatch']);
Route::get('dispatches/data', [DispatchController::class, 'getDispatchesData'])->name('dispatches.data');
Route::resource('dispatches', DispatchController::class);

Route::get('stock-pallets/report', [StockPalletController::class, 'showStockPalletReport'])->name('stock-pallets.report');
Route::get('stock-pallets/report-data', [StockPalletController::class, 'reportData'])->name('stock-pallets.report.data');


// Profile management
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
