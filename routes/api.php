<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RestaurantController;
use App\Http\Controllers\Api\FoodController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\BannerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/test', function () {
    return response()->json(['message' => 'API test route works']);
});

// Authentication Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function ()
{
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);

    // Order Routes that require authentication
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{orderId}', [OrderController::class, 'show']);
    Route::get('/my-orders', [OrderController::class, 'indexForUser']); // For user to view their orders
    Route::get('/orders/{id}/track', [OrderController::class, 'track']);
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->can('updateStatus,order');

    Route::post('/search/recent/add', [SearchController::class, 'addRecentSearch']);
    Route::get('/search/recent', [SearchController::class, 'getRecentSearches']);
    // public route for popular categories
    Route::get("/search/popular-categories", [SearchController::class, "getPopularCategories"]);
});

// Public Restaurant and Food Routes
Route::get('/restaurants', [RestaurantController::class, 'index']);
Route::get('/restaurants/search', [RestaurantController::class, 'search']); // Ensure this is before {restaurantId}
Route::get('/restaurants/{restaurantId}/foods', [FoodController::class, 'index']);
Route::get('/restaurants/{restaurantId}', [RestaurantController::class, 'show']);
Route::get('/restaurants/{restaurantId}/foods/{foodId}', [FoodController::class, 'show']);
Route::get('/restaurants/featured', [RestaurantController::class, 'featured']);
Route::get('/foods/popular', [FoodController::class, 'popular']);
// CRUD operations:
Route::apiResource('banners', \App\Http\Controllers\Api\BannerController::class);
Route::get('/banners', [BannerController::class, 'index']);
Route::get("/foods/search", [FoodController::class, "searchGlobal"]);


// Restaurant Management (Protected by policy)
Route::middleware(['auth:sanctum', 'can:manage-restaurant,restaurant
ın'])->group(function () {
    Route::put('/restaurants/{restaurant}', [RestaurantController::class, 'update']);
    Route::delete('/restaurants/{restaurant}', [RestaurantController::class, 'destroy']); // Uncomment if destroy is implemented
});

