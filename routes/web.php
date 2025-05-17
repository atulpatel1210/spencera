<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DesignController;
use App\Http\Controllers\FinishController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\PalletController;
use App\Http\Controllers\PurchaseOrderController;


Route::get('/', function () {
    return view('welcome');
});

// Dashboard
Route::get('/dashboard', [HomeController::class, 'index'])->middleware('auth')->name('dashboard');

// Design, Finish, Size, Pallet
Route::resource('designs', DesignController::class)->middleware('auth');
Route::resource('finishes', FinishController::class)->middleware('auth');
Route::resource('sizes', SizeController::class)->middleware('auth');
Route::resource('pallet', PalletController::class)->middleware('auth');
Route::resource('orders', PurchaseOrderController::class)->middleware('auth');
Route::get('orders/order-item-data/{id}', [PurchaseOrderController::class, 'getItemData']);
Route::patch('orders/update-order-item/{id}', [PurchaseOrderController::class, 'updateOrderItem']);

// Profile management
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
