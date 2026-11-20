<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\StudentdayController;
use App\Http\Controllers\StudentdayMemberController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TeachingModuleController;
use App\Http\Controllers\WaliKelasController;
use App\Http\Controllers\PiketModuleController;
use App\Http\Controllers\SpecialActivityController;
use App\Http\Controllers\PhModuleController;
use App\Http\Controllers\KadepModuleController;
use App\Http\Controllers\KepsekModuleController;
use App\Http\Controllers\LhoPrintController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GraduatedStudentController;
use App\Http\Controllers\ExitedStudentController;

Route::get('/', [AuthController::class, 'showFormLogin'])->name('login');
Route::post('/', [AuthController::class, 'login']);

Route::middleware(['auth'])->group(function () {
    Route::get('home', [HomeController::class, 'index'])->name('home');
    Route::post('customer-update-pass', [HomeController::class, 'customer_update_pass'])->name('home.customer-update-pass');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // Master Data Routes
    Route::resource('positions', PositionController::class)->except(['create', 'edit', 'show']);
    Route::resource('classes', ClassController::class)->except(['create', 'edit', 'show']);
    Route::resource('studentdays', StudentdayController::class)->except(['create', 'edit', 'show']);
    // Pengelolaan Anggota & Riwayat Siswa Studentday
    Route::get('studentdays-members', [StudentdayMemberController::class, 'index'])->name('studentday-members.index');
    Route::post('studentdays-members/add', [StudentdayMemberController::class, 'addMember'])->name('studentday-members.add');
    Route::delete('studentdays-members/{id}/remove', [StudentdayMemberController::class, 'removeMember'])->name('studentday-members.remove');
    Route::post('studentdays-members/{id}/exit', [StudentdayMemberController::class, 'exitMember'])->name('studentday-members.exit');
    Route::get('studentdays-members/history', [StudentdayMemberController::class, 'history'])->name('studentday-members.history');
    
    // Data Siswa & Upload Template Excel
    Route::get('students/template', [StudentController::class, 'downloadTemplate'])->name('students.template');
    Route::post('students/import', [StudentController::class, 'import'])->name('students.import');
    Route::resource('students', StudentController::class)->except(['create', 'edit', 'show']);

    // Siswa Lulus
    Route::get('graduated-students', [GraduatedStudentController::class, 'index'])->name('graduated-students.index');
    Route::get('graduated-students/get-students-by-class', [GraduatedStudentController::class, 'getStudentsByClass'])->name('graduated-students.get-students-by-class');
    Route::post('graduated-students/by-classes', [GraduatedStudentController::class, 'storeByClasses'])->name('graduated-students.store-by-classes');
    Route::post('graduated-students/by-students', [GraduatedStudentController::class, 'storeByStudents'])->name('graduated-students.store-by-students');
    Route::post('graduated-students/{id}/restore', [GraduatedStudentController::class, 'restore'])->name('graduated-students.restore');
    Route::delete('graduated-students/{id}', [GraduatedStudentController::class, 'destroy'])->name('graduated-students.destroy');

    // Siswa Keluar
    Route::get('exited-students', [ExitedStudentController::class, 'index'])->name('exited-students.index');
    Route::get('exited-students/search-students', [ExitedStudentController::class, 'searchActiveStudents'])->name('exited-students.search-students');
    Route::post('exited-students', [ExitedStudentController::class, 'store'])->name('exited-students.store');
    Route::post('exited-students/{id}/restore', [ExitedStudentController::class, 'restore'])->name('exited-students.restore');
    Route::delete('exited-students/{id}', [ExitedStudentController::class, 'destroy'])->name('exited-students.destroy');

    Route::resource('users', UserController::class)->except(['create', 'edit', 'show']);
    Route::post('users/{id}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');

    // Profil & Ubah Password Sendiri (Setiap User)
    Route::get('profile/password', [ProfileController::class, 'showChangePassword'])->name('password.change');
    Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('password.update');

    // Modul Guru Kelas (Presensi & Jurnal KBM)
    Route::get('teaching', [TeachingModuleController::class, 'index'])->name('teaching.index');
    Route::post('teaching', [TeachingModuleController::class, 'store'])->name('teaching.store');
    Route::get('teaching/history', [TeachingModuleController::class, 'history'])->name('teaching.history');
    Route::post('teaching/student-notes', [TeachingModuleController::class, 'storeStudentNote'])->name('teaching.student-notes.store');

    // Modul Wali Kelas (Monitoring Kelas Binaan - ReadOnly)
    Route::get('walikelas/dashboard', [WaliKelasController::class, 'index'])->name('walikelas.dashboard');

    // Modul Guru Piket
    Route::get('piket/dashboard', [PiketModuleController::class, 'index'])->name('piket.dashboard');
    Route::get('piket/student-absences', [PiketModuleController::class, 'studentAbsences'])->name('piket.student-absences');
    Route::put('piket/student-absences/{id}', [PiketModuleController::class, 'updateStudentAbsence'])->name('piket.student-absences.update');
    Route::put('piket/student-notes/{id}', [PiketModuleController::class, 'updateStudentNote'])->name('piket.student-notes.update');
    Route::delete('piket/student-notes/{id}', [PiketModuleController::class, 'destroyStudentNote'])->name('piket.student-notes.destroy');
    
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

    // Pengelolaan Lampiran Dokumen LHO
    Route::delete('lho/attachments/{id}', [PhModuleController::class, 'destroyAttachment'])->name('lho.attachments.destroy');

    // Cetak / Ekspor LHO
    Route::get('lho/print', [LhoPrintController::class, 'print'])->name('lho.print');
});






