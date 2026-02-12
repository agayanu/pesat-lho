<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;

Route::get('/', [AuthController::class, 'showFormLogin'])->name('login');
Route::post('/', [AuthController::class, 'login']);

Route::middleware(['auth'])->group(function () {
    Route::get('home', [HomeController::class, 'index'])->name('home');
    Route::post('customer-update-pass', [HomeController::class, 'customer_update_pass'])->name('home.customer-update-pass');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    
});
