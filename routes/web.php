<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;

Route::get('/', [AuthController::class, 'showFormLogin'])->name('login');
Route::post('/', [AuthController::class, 'login']);

Route::middleware(['auth'])->group(function () {
    Route::get('home', [HomeController::class, 'index'])->name('home');
    Route::post('customer-update-pass', [HomeController::class, 'customer_update_pass'])->name('home.customer-update-pass');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // Master Data Routes
    Route::resource('positions', PositionController::class)->except(['create', 'edit', 'show']);
    Route::resource('teachers', TeacherController::class)->except(['create', 'edit', 'show']);
    Route::resource('classes', ClassController::class)->except(['create', 'edit', 'show']);
    Route::resource('students', StudentController::class)->except(['create', 'edit', 'show']);
    Route::resource('users', UserController::class)->except(['create', 'edit', 'show']);
});

