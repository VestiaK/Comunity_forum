<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Requests\LoginRequest;



Route::get('/register', [RegisterController::class, 'index']); 
Route::post('/register', [RegisterController::class, 'store']);

Route::get('/login', [LoginRequest::class, 'index'])->middleware('guest')->name('login');
Route::post('/login', [LoginRequest::class, 'authenticate']);
Route::post('/logout', [LoginRequest::class, 'logout'])->middleware('auth');
