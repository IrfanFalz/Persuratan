<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardGuruController;
use App\Http\Controllers\DashboardKtuController;
use App\Http\Controllers\DashboardTuController;
use App\Http\Controllers\DashboardKepsekController;
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\FormSuratController;

// Root
Route::get('/', [AuthController::class, 'redirectRoot']);

// Auth
Route::match(['get','post'], '/login', [AuthController::class, 'login'])->name('login');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard Guru
Route::middleware('checkRole:GURU')->get('/dashboard/guru', [DashboardGuruController::class, 'index'])->name('dashboard.guru');

/* Dashboard KTU
Route::middleware('checkRole:KTU')->get('/dashboard/ktu', [DashboardKtuController::class, 'index'])->name('dashboard.ktu');
Route::middleware('checkRole:KTU')->post('/dashboard/ktu/approval', [DashboardKtuController::class, 'approval'])->name('dashboard.ktu.approval');
*/

// Dashboard TU
Route::middleware('checkRole:TU')->get('/dashboard/tu', [DashboardTuController::class, 'index'])->name('dashboard.tu');
Route::middleware('checkRole:TU')->post('/dashboard/tu/process', [DashboardTuController::class, 'process'])->name('dashboard.tu.process');
Route::post('/dashboard/tu/resend-notification', [DashboardTuController::class, 'resendNotification'])->name('dashboard.tu.resend-notification');

// Dashboard Kepsek
Route::middleware('checkRole:KEPSEK')->get('/dashboard/kepsek', [DashboardKepsekController::class, 'index'])->name('dashboard.kepsek');
Route::middleware('checkRole:KEPSEK')->post('/dashboard/kepsek/approval', [DashboardKepsekController::class, 'approval'])->name('dashboard.kepsek.approval');

// Dashboard Admin
Route::middleware('checkRole:ADMIN')->group(function () {
    Route::get('/dashboard/admin', [DashboardAdminController::class, 'index'])->name('dashboard.admin');
    Route::get('/admin/kelola-guru', [DashboardAdminController::class, 'kelolaGuru'])->name('admin.kelola-guru');
    Route::get('/admin/kelola-surat', [DashboardAdminController::class, 'kelolaSurat'])->name('admin.kelola-surat');
    Route::get('/admin/history-surat', [DashboardAdminController::class, 'historySurat'])->name('admin.history-surat');
    Route::get('/admin/template/{id}', [DashboardAdminController::class, 'viewTemplate'])->name('admin.view-template');
});

// Form Surat
Route::middleware('checkRole:GURU')->get('/form-surat', [FormSuratController::class, 'index'])->name('form.surat');
Route::middleware('checkRole:GURU')->post('/form-surat', [FormSuratController::class, 'submit']);
Route::middleware('checkRole:GURU')->get('/guru-data', [FormSuratController::class, 'getGuruData'])->name('guru.data');
Route::middleware('checkRole:GURU')->get('/siswa-data', [FormSuratController::class, 'getSiswaData'])->name('siswa.data');

// ================== LOGOUT ==================
Route::get('/logout', function () {
    session()->flush();
    return redirect()->route('login'); // tanpa flash message
})->name('logout');