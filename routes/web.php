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
use App\Http\Controllers\SpecialActivityController;
use App\Http\Controllers\PhModuleController;
use App\Http\Controllers\KadepModuleController;
use App\Http\Controllers\KepsekModuleController;
use App\Http\Controllers\LhoPrintController;

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

    // Modul Penanggung Jawab Kegiatan Spesifik
    Route::get('special-activities', [SpecialActivityController::class, 'index'])->name('special-activities.index');
    Route::post('special-activities', [SpecialActivityController::class, 'store'])->name('special-activities.store');
    Route::delete('special-activities/{id}', [SpecialActivityController::class, 'destroy'])->name('special-activities.destroy');

    // Modul PH (Penanggung Jawab Harian)
    Route::get('ph/dashboard', [PhModuleController::class, 'index'])->name('ph.dashboard');
    Route::post('ph/notes', [PhModuleController::class, 'storeNotes'])->name('ph.notes.store');

    // Modul Kepala Departemen
    Route::get('kadep/dashboard', [KadepModuleController::class, 'index'])->name('kadep.dashboard');
    Route::post('kadep/notes', [KadepModuleController::class, 'storeNotes'])->name('kadep.notes.store');

    // Modul Kepala Sekolah
    Route::get('kepsek/dashboard', [KepsekModuleController::class, 'index'])->name('kepsek.dashboard');
    Route::post('kepsek/notes', [KepsekModuleController::class, 'storeNotes'])->name('kepsek.notes.store');

    // Cetak / Ekspor LHO
    Route::get('lho/print', [LhoPrintController::class, 'print'])->name('lho.print');
});






