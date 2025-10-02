<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardGuruController;
use App\Http\Controllers\DashboardKtuController;
use App\Http\Controllers\DashboardTuController;
use App\Http\Controllers\DashboardKepsekController;
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\FormSuratController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\PersetujuanController;
use App\Http\Controllers\TUController;

Route::get('/', [AuthController::class, 'redirectRoot']);

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/logout', fn () => redirect()->route('login'));

Route::middleware(['auth','checkRole:GURU'])->group(function () {
    Route::get('/dashboard/guru', [DashboardGuruController::class, 'index'])->name('dashboard.guru');

    Route::get('/form-surat', [FormSuratController::class, 'index'])->name('form.surat');
    Route::post('/form-surat', [FormSuratController::class, 'submit']);
    Route::get('/guru-data', [FormSuratController::class, 'getGuruData'])->name('guru.data');
    Route::get('/siswa-data', [FormSuratController::class, 'getSiswaData'])->name('siswa.data');

    Route::post('/surat', [SuratController::class, 'store'])->name('surat.store');
    Route::get('/surat/my', [SuratController::class, 'mySurat'])->name('surat.my');
});

Route::middleware(['auth','checkRole:TU'])->group(function () {
    Route::get('/dashboard/tu', [DashboardTuController::class, 'index'])->name('dashboard.tu');
    Route::post('/dashboard/tu/process', [DashboardTuController::class, 'process'])->name('dashboard.tu.process');
    Route::post('/dashboard/tu/resend-notification', [DashboardTuController::class, 'resendNotification'])->name('dashboard.tu.resend-notification');

    Route::post('/surat/{id}/generate', [TUController::class, 'generatePdf'])->name('surat.generate');
});

Route::middleware(['auth','checkRole:KEPSEK'])->group(function () {
    Route::get('/dashboard/kepsek', [DashboardKepsekController::class, 'index'])->name('dashboard.kepsek');
    
    Route::post('/dashboard/kepsek/approval', [DashboardKepsekController::class, 'approval'])->name('dashboard.kepsek.approval');
    
    Route::get('/surat/pending', [PersetujuanController::class, 'listPending'])->name('surat.pending');
    Route::post('/persetujuan/{id}', [PersetujuanController::class, 'update'])->name('persetujuan.update');
});

Route::middleware(['auth','checkRole:ADMIN'])->group(function () {
    Route::get('/dashboard/admin', [DashboardAdminController::class, 'index'])->name('dashboard.admin');
    Route::get('/admin/history-surat', [DashboardAdminController::class, 'historySurat'])->name('admin.history-surat');

    Route::get('/admin/users', [DashboardAdminController::class, 'usersIndex'])->name('admin.users.index');
    Route::post('/admin/users', [DashboardAdminController::class, 'usersStore'])->name('admin.users.store');
    Route::post('/admin/users/{id}', [DashboardAdminController::class, 'usersUpdate'])->name('admin.users.update');
    Route::delete('/admin/users/{id}', [DashboardAdminController::class, 'usersDelete'])->name('admin.users.delete');

    Route::get('/admin/templates', [DashboardAdminController::class, 'templatesIndex'])->name('admin.templates.index');
    Route::post('/admin/templates', [DashboardAdminController::class, 'templatesStore'])->name('admin.templates.store');
    Route::post('/admin/templates/{id}', [DashboardAdminController::class, 'templatesUpdate'])->name('admin.templates.update');
    Route::delete('/admin/templates/{id}', [DashboardAdminController::class, 'templatesDelete'])->name('admin.templates.delete');
    Route::get('/admin/template/{id}', [DashboardAdminController::class, 'viewTemplate'])->name('admin.view-template');

    Route::get('/admin/kelola-guru', [DashboardAdminController::class, 'kelolaGuru'])->name('admin.kelola-guru');
    Route::get('/admin/kelola-surat', [DashboardAdminController::class, 'kelolaSurat'])->name('admin.kelola-surat');
});
