<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\Auth\PasswordController;

Route::prefix('admin')->middleware('guest:admin')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('admin.register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [LoginController::class, 'create'])
        ->name('admin.login');

    Route::post('login', [LoginController::class, 'store']);
});

Route::prefix('admin')->middleware('auth:admin')->group(function () {

    Route::get('/profile', [ProfileController::class, 'adminedit'])->name('admin.profile.edit');
    Route::patch('/profile', [ProfileController::class, 'adminupdate'])->name('admin.profile.update');
    Route::delete('/profile', [ProfileController::class, 'admindestroy'])->name('admin.profile.destroy');
    Route::put('password', [PasswordController::class, 'adminupdate'])->name('admin.password.update');
    
    Route::post('logout', [LoginController::class, 'destroy'])
        ->name('admin.logout');

    Route::get('/dashboard',[InventoryController::class,'loadAllInventories'])->name('admin.dashboard');
    Route::get('/inventory/add/',[InventoryController::class,'loadAddInventoryForm']);
    Route::get('/inventory/add/{id}',[InventoryController::class,'loadAddInventoryFormFromOrder']);
    Route::post('/inventory/add',[InventoryController::class,'AddInventory'])->name('admin.AddInventory');
    Route::get('/inventory/edit/{id}',[InventoryController::class,'loadEditForm']);
    Route::get('/inventory/delete/{id}',[InventoryController::class,'deleteInventory']);
    Route::post('/inventory/edit',[InventoryController::class,'EditInventory'])->name('admin.EditInventory');

    Route::get('/product',[ProductController::class,'loadAllProducts'])->name('admin.product');

    Route::get('/order',[OrderController::class,'loadAllOrders'])->name('admin.order');
    Route::get('/order/add/{id}',[OrderController::class,'loadAddOrderForm']);
    Route::post('/order/add',[OrderController::class,'AddOrder'])->name('admin.AddOrder');
    Route::get('/order/edit/{id}',[OrderController::class,'loadEditForm']);
    Route::get('/order/delete/{id}',[OrderController::class,'deleteOrder']);
    Route::post('/order/edit',[OrderController::class,'EditOrder'])->name('admin.EditOrder');

});
