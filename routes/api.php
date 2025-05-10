<?php

//use App\Http\Controllers\AuthController;
//use App\Http\Controllers\UserController;
//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RestaurantController;
use App\Http\Controllers\Api\FoodController;
use App\Http\Controllers\Api\OrderController;


//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    //return $request->user();
//});

//Route::post('/register', [AuthController::class, 'register']);
//Route::post('/login', [AuthController::class, 'login']);

//Route::middleware('auth:sanctum')->group(function () {
    //Route::get('/profile', [UserController::class, 'profile']);
    //Route::post('/logout', [AuthController::class, 'logout']);
//});

Route::get('/restaurants', [RestaurantController::class, 'index']);
Route::get('/restaurants/{id}/foods', [FoodController::class, 'index']);
Route::post('/orders', [OrderController::class, 'store']);

Route::get('/restaurants', [RestaurantController::class, 'index']);
Route::get('/restaurants/{restaurantId}', [RestaurantController::class, 'show']);
Route::get('/restaurants/search', [RestaurantController::class, 'search']);

Route::get('/restaurants/{restaurantId}/foods', [FoodController::class, 'index']);
Route::get('/restaurants/{restaurantId}/foods/{foodId}', [FoodController::class, 'show']);

Route::post('/orders', [OrderController::class, 'store'])->middleware('auth:sanctum');
Route::get('/orders/{orderId}', [OrderController::class, 'show'])->middleware('auth:sanctum');

Route::middleware(['auth:sanctum', 'can:manage-restaurant,restaurant'])->group(function () {
    Route::put('/restaurants/{restaurant}', [RestaurantController::class, 'update']);
    Route::delete('/restaurants/{restaurant}', [RestaurantController::class, 'destroy']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/orders/{order}', [OrderController::class, 'show'])->can('view,order');
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->can('updateStatus,order');
});



