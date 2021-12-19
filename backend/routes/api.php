<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:api'])->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
    /** User routes - START*/
    Route::post('/user/search', [UserController::class, 'search'])->name('user.search');
    Route::post('/user/lock', [UserController::class, 'lockUser'])->name('user.lock');
    Route::post('/user/delete', [UserController::class, 'deleteUser'])->name('user.delete');
    Route::post('/user/store', [UserController::class, 'storeUser'])->name('user.store');
    Route::post('/user/save', [UserController::class, 'saveUser'])->name('user.save');
    /** User routes - END*/

    /** Product routes - START */
    Route::post('/products/search', [ProductController::class, 'search'])->name('product.search');
    Route::post('/products/delete', [ProductController::class, 'deleteProduct'])->name('product.delete');
    Route::post('/products/store', [ProductController::class, 'storeProduct'])->name('product.store');
    Route::post('/products/save', [ProductController::class, 'saveProduct'])->name('product.save');
    Route::post('/products/image/delete', [ProductController::class, 'deleteProductImage'])->name('product.image.delete');
    /** Product routes - END */
});

