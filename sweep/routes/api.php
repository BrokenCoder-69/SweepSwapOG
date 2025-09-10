<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\UserDashboardController;

use App\Http\Controllers\AuthController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\SwapController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\ReportController;

// Public routes
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/forgot-password', [AuthController::class, 'forgot_password'])->name('forgot-password');
Route::post('/reset-password', [AuthController::class, 'reset_password'])->name('reset-password');

// Public product listing + show (anyone can view products)
Route::get('products', [ProductController::class, 'index']);
Route::get('products/{id}', [ProductController::class, 'show']);

// Protected routes

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', function (Request $request) {
        $request->user()->tokens()->delete();
        return response()->json(['success' => true, 'message' => 'Logged out']);
    })->name('logout');

    Route::put('/user/update', [AuthController::class, 'update']);
    Route::get('/user', [AuthController::class, 'getUser']);

    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
        ->middleware('admin')
        ->name('admin.dashboard');

    Route::get('/user/dashboard', [UserDashboardController::class, 'index'])
        ->middleware('user')
        ->name('user.dashboard');

    // 🔒 Protected product routes (only logged-in users can modify)
    Route::post('products', [ProductController::class, 'store']);
    Route::put('products/{id}', [ProductController::class, 'update']);
    Route::delete('products/{id}', [ProductController::class, 'destroy']);

    // Wishlist routes
    Route::get('wishlist', [WishlistController::class, 'index']);
    Route::post('wishlist/{productId}', [WishlistController::class, 'add']);
    Route::delete('wishlist/{productId}', [WishlistController::class, 'remove']);

    // Cart routes
    Route::get('cart', [CartController::class, 'index']);
    Route::post('cart/{productId}', [CartController::class, 'add']);
    Route::delete('cart/{productId}', [CartController::class, 'remove']);

    // Message routes
    Route::post('messages/{receiverId}', [MessageController::class, 'send']);
    Route::get('messages/{userId}', [MessageController::class, 'conversation']);

    // Swap routes
    Route::get('swaps', [SwapController::class, 'index']);
    Route::post('products/{proposeeProductId}/propose-swap', [SwapController::class, 'propose']);
    Route::post('swaps/{id}/accept', [SwapController::class, 'accept']);
    Route::post('swaps/{id}/reject', [SwapController::class, 'reject']);
    Route::get('swaps/notifications', [SwapController::class, 'notification']);


    // Purchase routes
    Route::get('purchases', [PurchaseController::class, 'index']);                  // List all purchases
    Route::post('products/buy/{productId}', [PurchaseController::class, 'buy']);   // Create a new purchase
    Route::get('purchases/{purchase}', [PurchaseController::class, 'show']);        // Show one purchase
    Route::put('purchases/{purchase}', [PurchaseController::class, 'update']);      // Update a purchase
    Route::delete('purchases/{purchase}', [PurchaseController::class, 'destroy']);  // Delete a purchase


    // Ratings
    Route::post('users/rate/{id}', [RatingController::class, 'store']);

    // Reports
    Route::post('users/report/{id}', [ReportController::class, 'store']);

    //Dedicated users product
    Route::get('users/products' , [SwapController::class, 'product_list']);






});

Route::get('users/{id}/ratings', [RatingController::class, 'show']);



Route::middleware('auth:sanctum' , AdminMiddleware::class)->group(function () {
    Route::get('admin/reports', [ReportController::class, 'index']);
    Route::put('admin/reports/feedback/{id}', [ReportController::class, 'feedback']);





});








// // Public routes
// Route::post('/login', [AuthController::class, 'login'])->name('login');
// Route::post('/register', [AuthController::class, 'register'])->name('register');
// Route::post('/forgot-password', [AuthController::class, 'forgot_password'])->name('forgot-password'); 
// Route::post('/reset-password', [AuthController::class, 'reset_password'])->name('reset-password');




// // Public product listing + show (anyone can view products)
// Route::get('products', [ProductController::class, 'index']);
// Route::get('products/{id}', [ProductController::class, 'show']);




// // Protected routes
// Route::middleware('auth:sanctum')->group(function () {
//     Route::post('/logout', function (Request $request) {
//         $request->user()->tokens()->delete();
//         return response()->json(['success' => true, 'message' => 'Logged out']);
//     })->name('logout');

//     Route::get('/user', function (Request $request) {
//         return $request->user();
//     });

//     Route::put('/user/update', [AuthController::class, 'update']);
//     Route::get('/user', [AuthController::class, 'getUser']);

//     Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->middleware('admin')->name('admin.dashboard');
//     Route::get('/user/dashboard', [UserDashboardController::class, 'index'])->middleware('user')->name('user.dashboard');});


// // ----- Sprint 2 -----



//     // Product routes
//     Route::apiResource('products', ProductController::class);

//     // Wishlist routes
//     Route::get('wishlist', [WishlistController::class, 'index']);
//     Route::post('wishlist/{productId}', [WishlistController::class, 'add']);
//     Route::delete('wishlist/{productId}', [WishlistController::class, 'remove']);

//     // Cart routes
//     Route::get('cart', [CartController::class, 'index']);
//     Route::post('cart/{productId}', [CartController::class, 'add']);
//     Route::delete('cart/{productId}', [CartController::class, 'remove']);

//     // Message routes
//     Route::post('messages/{receiverId}', [MessageController::class, 'send']);
//     Route::get('messages/{userId}', [MessageController::class, 'conversation']);

//     // Swap routes
//     Route::get('swaps', [SwapController::class, 'index']);
//     Route::post('products/{proposeeProductId}/propose-swap', [SwapController::class, 'propose']);
//     Route::post('swaps/{id}/accept', [SwapController::class, 'accept']);
//     Route::post('swaps/{id}/reject', [SwapController::class, 'reject']);

//     // Purchase routes
//     Route::post('products/{productId}/buy', [PurchaseController::class, 'buy']);
