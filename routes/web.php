<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardGuruController;
use App\Http\Controllers\DashboardKtuController;
use App\Http\Controllers\DashboardTuController;
use App\Http\Controllers\DashboardKepsekController;
use App\Http\Controllers\FormSuratController;

// Root
Route::get('/', [AuthController::class, 'redirectRoot']);

// Auth
Route::match(['get','post'], '/login', [AuthController::class, 'login'])->name('login');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard Guru
Route::middleware('checkRole:GURU')->get('/dashboard/guru', [DashboardGuruController::class, 'index'])->name('dashboard.guru');

// Dashboard KTU
Route::middleware('checkRole:KTU')->get('/dashboard/ktu', [DashboardKtuController::class, 'index'])->name('dashboard.ktu');
Route::middleware('checkRole:KTU')->post('/dashboard/ktu/approval', [DashboardKtuController::class, 'approval'])->name('dashboard.ktu.approval');

// Dashboard TU
Route::middleware('checkRole:TU')->get('/dashboard/tu', [DashboardTuController::class, 'index'])->name('dashboard.tu');
Route::middleware('checkRole:TU')->post('/dashboard/tu/process', [DashboardTuController::class, 'process'])->name('dashboard.tu.process');

// Dashboard Kepsek
Route::middleware('checkRole:KEPSEK')->get('/dashboard/kepsek', [DashboardKepsekController::class, 'index'])->name('dashboard.kepsek');
Route::middleware('checkRole:KEPSEK')->post('/dashboard/kepsek/approval', [DashboardKepsekController::class, 'approval'])->name('dashboard.kepsek.approval');

// Form Surat
Route::middleware('checkRole:GURU')->get('/form-surat', [FormSuratController::class, 'index'])->name('form.surat');
Route::middleware('checkRole:GURU')->post('/form-surat', [FormSuratController::class, 'submit']);

// ================== LOGOUT ==================
Route::get('/logout', function () {
    session()->flush();
    return redirect()->route('login'); // tanpa flash message
})->name('logout');
