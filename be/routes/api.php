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
            Route::put('/{id}/toggle-ban', [UserController::class, 'toggleBan']);
            Route::put('/{id}/toggle-admin', [UserController::class, 'toggleAdmin']);
        });
    });

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
});
