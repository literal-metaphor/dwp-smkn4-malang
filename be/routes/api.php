<?php

use App\Http\Controllers\UserController;
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
            Route::get('/{id}/toggle-ban', [UserController::class, 'toggleBan']);
            Route::get('/{id}/toggle-admin', [UserController::class, 'toggleAdmin']);
        });
    });

    Route::prefix('/users')->group(function () {
        // Read methods should be available to all users, not just admins
        Route::get('/', [UserController::class, 'index']);
        Route::get('/{id}', [UserController::class, 'show']);

        // These routes should be protected whether to the authorized user or admins
        // Route::post('/', [UserController::class, 'store']); // !deprecated
        Route::put('/{id}', [UserController::class, 'update']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
        Route::get('/{id}/avatar', [UserController::class, 'getAvatar']);
        Route::post('/{id}/avatar', [UserController::class, 'storeAvatar']);
        // Route::put('/{id}/avatar', [UserController::class, 'updateAvatar']); // !combined with store method
        Route::delete('/{id}/avatar', [UserController::class, 'destroyAvatar']);
    });
});
