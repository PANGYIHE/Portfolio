<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\InventoryController;

Route::get('/admin', function (Request $request) {
    return response()->json([
        'success' => true,
        'admin' => $request->user(), // Returns the authenticated admin
    ]);
})->middleware('auth:sanctum');

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::delete('/logout', [AuthController::class, 'logout']);
    Route::delete('/admin', [AuthController::class, 'deleteAdmin']);
    Route::get('/inventory', [InventoryController::class, 'getUserInventories']);
    Route::post('/inventory', [InventoryController::class, 'addUserInventories']);
    Route::patch('/inventory/{id}', [InventoryController::class, 'updateUserInventories']);
    Route::delete('/inventory/{id}', [InventoryController::class, 'deleteUserInventory']);
});