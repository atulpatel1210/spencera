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


Route::get('/', function () {
    return view('welcome');
});

// Dashboard
Route::get('/dashboard', [HomeController::class, 'index'])->middleware('auth')->name('dashboard');

Route::get('parties/data', [PartyController::class, 'getPartiesData'])->name('parties.data');
Route::resource('parties', PartyController::class);
Route::get('designs/data', [DesignController::class, 'getDesignsData'])->name('designs.data');
Route::resource('designs', DesignController::class)->middleware('auth');
Route::get('finishes/data', [FinishController::class, 'getFinishesData'])->name('finishes.data');
Route::resource('finishes', FinishController::class)->middleware('auth');
Route::get('sizes/data', [SizeController::class, 'getSizesData'])->name('sizes.data');
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

// Profile management
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
