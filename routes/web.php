<?php

use App\Http\Controllers\Api\UserApiController;
use Illuminate\Support\Facades\Route;


Route::get('/', [UserApiController::class, 'create'])->name('register');

Route::post('/', [UserApiController::class, 'store'])->name('register');

Route::post('/login', [UserApiController::class, 'login'])->name('login');

