<?php

use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Middleware\AdminOnly;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// *V1 Routes
Route::prefix('v1')->group(function () {
    // * Authentication Routes
    Route::prefix('/auth')->group(function () {
        Route::post('/', [UserController::class, 'register']);
        Route::get('/{id}', [UserController::class, 'verify']);
        Route::put('/', [UserController::class, 'login']);
        Route::delete('/{id}', [UserController::class, 'logout']);

        Route::prefix('/admin')->group(function () {
            Route::put('/{id}/toggle-ban', [UserController::class, 'toggleBan']);
            Route::put('/{id}/toggle-admin', [UserController::class, 'toggleAdmin']);
        });
    });

    // * User Routes
    Route::prefix('/user')->group(function () {
        // Read methods should be available to all users, not just admins
        Route::get('/', [UserController::class, 'index']);
        Route::get('/{id}', [UserController::class, 'show']);

        // These routes should be protected whether to the authorized user or admins
        // Route::post('/', [UserController::class, 'store']); // !deprecated
        Route::put('/{id}', [UserController::class, 'update']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
        Route::get('/{id}/avatar', [UserController::class, 'getAvatar']);
        Route::post('/{id}/avatar', [UserController::class, 'storeAvatar']);
        // Route::put('/{id}/avatar', [UserController::class, 'updateAvatar']); // !combined with store method, also Laravel doesn't support form data in PUT requests, which mean sending file data is not possible at the moment
        Route::delete('/{id}/avatar', [UserController::class, 'destroyAvatar']);
    });

    // * Shop Routes
    Route::prefix('/shop')->group(function () {
        Route::get('/', [ShopController::class, 'index']);
        Route::get('/{id}', [ShopController::class, 'show']);
        Route::middleware([AdminOnly::class])->group(function () {
            Route::post('/', [ShopController::class, 'store']);
            Route::put('/{id}', [ShopController::class, 'update']);
            Route::delete('/{id}', [ShopController::class, 'destroy']);
        });
    });

    // * Product Routes
    Route::prefix('/product')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/{id}', [ProductController::class, 'show']);
        Route::post('/', [ProductController::class, 'store']);
        Route::put('/{id}', [ProductController::class, 'update']);
        Route::delete('/{shop_id}/{id}', [ProductController::class, 'destroy']);

        Route::prefix('/photo')->group(function () {
            Route::get('/{id}', [ProductController::class, 'getPhotos']);
            Route::post('/{shop_id}/{id}', [ProductController::class, 'addPhoto']);
            Route::delete('/{shop_id}/{id}/{photo_id}', [ProductController::class, 'deletePhoto']);
        });
    });
});
