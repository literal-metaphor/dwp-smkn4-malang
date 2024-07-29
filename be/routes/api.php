<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\TransactionController;
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
        Route::post('/oauth', [UserController::class, 'oauth']);
        Route::put('/{id}/password', [UserController::class, 'changePassword']);

        Route::prefix('/admin')->group(function () {
            Route::put('/{id}/toggle-ban', [UserController::class, 'toggleBan']);
            Route::put('/{id}/toggle-admin', [UserController::class, 'toggleAdmin']);
            Route::put('/{id}/toggle-shop', [UserController::class, 'toggleShop']);
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
        Route::get('/avatar/{id}', [UserController::class, 'getAvatar']);
        Route::post('/avatar/{id}', [UserController::class, 'storeAvatar']);
        // Route::put('/avatar/{id}', [UserController::class, 'updateAvatar']); // !combined with store method, also Laravel doesn't support form data in PUT requests, which mean sending file data is not possible at the moment
        Route::delete('/avatar/{id}', [UserController::class, 'destroyAvatar']);
    });

    // * Product Routes
    Route::prefix('/product')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/paginate', [ProductController::class, 'paginate']);
        Route::get('/{id}', [ProductController::class, 'show']);
        Route::post('/', [ProductController::class, 'store']);
        Route::put('/{id}', [ProductController::class, 'update']);
        Route::delete('/{id}', [ProductController::class, 'destroy']);

        Route::get('/owner/{owner_id}', [ProductController::class, 'indexByOwner']);

        Route::prefix('/photo')->group(function () {
            Route::get('/{id}', [ProductController::class, 'getPhotos']);
            Route::post('/{id}', [ProductController::class, 'addPhoto']);
            Route::delete('/{id}', [ProductController::class, 'deletePhoto']);
        });

        Route::post('/wishlist/{user_id}/{id}', [ProductController::class, 'toggleWishlist']);
        Route::get('/wishlist/{user_id}', [ProductController::class, 'getWishlist']);
        Route::get('/wishlist/{user_id}/{id}', [ProductController::class, 'isWishlist']);
    });

    // * Transaction Routes
    Route::prefix('/transaction')->group(function () {
        Route::get('/{id}', [TransactionController::class, 'show']);
        Route::post('/', [TransactionController::class, 'store']);

        Route::middleware([AdminOnly::class])->group(function () {
            Route::get('/', [TransactionController::class, 'index']);
            // Route::put('/{id}', [TransactionController::class, 'update']); // I'm not sure there would be an instance where admin would need to update a transaction detail, so I'm not implementing update yet.
            Route::delete('/{id}', [TransactionController::class, 'destroy']);
        });
    });

    // * Rating Routes
    Route::prefix('/rating')->group(function () {
        Route::get('/', [RatingController::class, 'index']);
        Route::get('/product/{product_id}', [RatingController::class, 'indexByProduct']);
        Route::get('/{id}', [RatingController::class, 'show']);
        Route::post('/', [RatingController::class, 'store']);
        // Route::put('/{id}', [RatingController::class, 'update']); // Combined with store
        Route::delete('/{id}', [RatingController::class, 'destroy']);

        Route::get('/user/{product_id}', [RatingController::class, 'getRatingByUser']);
    });

    // * Comment Routes
    Route::prefix('/comment')->group(function () {
        Route::get('/', [CommentController::class, 'index']);
        Route::get('/product/{product_id}', [CommentController::class, 'indexByProduct']);
        Route::get('/{id}', [CommentController::class, 'show']);
        Route::post('/', [CommentController::class, 'store']);
        Route::put('/{id}', [CommentController::class, 'update']);
        Route::delete('/{id}', [CommentController::class, 'destroy']);

        Route::prefix('/photo')->group(function () {
            Route::get('/{id}', [CommentController::class, 'getPhotos']);
            Route::post('/{id}', [CommentController::class, 'addPhoto']);
            Route::delete('/{comment_id}/{photo_id}', [CommentController::class, 'deletePhoto']);
        });
    });
});
