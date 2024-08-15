<?php

use App\Http\Controllers\Api\EventApiController;
use App\Http\Controllers\Api\OrderApiController;
use App\Http\Controllers\Api\OrdersIntentApiController;
use App\Http\Controllers\Api\TicketsTypeApiController;
use App\Http\Controllers\Api\UserApiController;
use Illuminate\Support\Facades\Route;


Route::get('/', [UserApiController::class, 'create'])->name('register');

Route::post('/', [UserApiController::class, 'store'])->name('register');

Route::post('/login', [UserApiController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [UserApiController::class, 'logout'])->name('logout');
    
    Route::get('/events', [EventApiController::class, 'index']);
    
    Route::get('/events/upcoming', [EventApiController::class, 'indexUpcoming']);
        
    Route::get('/events/ticket/types/{event_id}', [TicketsTypeApiController::class, 'index']);
    
    Route::post('/orders/intent', [OrdersIntentApiController::class, 'store']);
    
    Route::post('/orders/validate/{order_intent_id}/{event_id}', [OrderApiController::class, 'store']);
    
    Route::get('/orders/user/{user_id}', [OrderApiController::class, 'user_index']);
});






