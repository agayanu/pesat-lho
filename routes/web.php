<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TeachingModuleController;
use App\Http\Controllers\PiketModuleController;

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

    // Modul Guru Kelas (Presensi & Jurnal KBM)
    Route::get('teaching', [TeachingModuleController::class, 'index'])->name('teaching.index');
    Route::post('teaching', [TeachingModuleController::class, 'store'])->name('teaching.store');
    Route::get('teaching/history', [TeachingModuleController::class, 'history'])->name('teaching.history');

    // Modul Guru Piket
    Route::get('piket/dashboard', [PiketModuleController::class, 'index'])->name('piket.dashboard');
    Route::get('piket/student-absences', [PiketModuleController::class, 'studentAbsences'])->name('piket.student-absences');
    Route::put('piket/student-absences/{id}', [PiketModuleController::class, 'updateStudentAbsence'])->name('piket.student-absences.update');
    
    Route::get('piket/teacher-absences', [PiketModuleController::class, 'teacherAbsences'])->name('piket.teacher-absences');
    Route::post('piket/teacher-absences', [PiketModuleController::class, 'storeTeacherAbsence'])->name('piket.teacher-absences.store');
    Route::delete('piket/teacher-absences/{id}', [PiketModuleController::class, 'destroyTeacherAbsence'])->name('piket.teacher-absences.destroy');

    Route::get('piket/school-events', [PiketModuleController::class, 'schoolEvents'])->name('piket.school-events');
    Route::post('piket/school-events', [PiketModuleController::class, 'storeSchoolEvent'])->name('piket.school-events.store');
    Route::delete('piket/school-events/{id}', [PiketModuleController::class, 'destroySchoolEvent'])->name('piket.school-events.destroy');
});



