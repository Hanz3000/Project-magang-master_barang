<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\MasterBarangController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DivisiController;

// ========== AUTH (LOGIN / REGISTER / LOGOUT) ==========
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ========== LUPA PASSWORD ==========
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');

// ========== SETELAH LOGIN ADMIN ==========
Route::middleware('auth:admin')->group(function () {

    // === Dashboard ===
    Route::get('/', [AuthController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/filter', [DashboardController::class, 'filter'])->name('dashboard.filter');
    Route::get('/dashboard/filter/month', [DashboardController::class, 'filterMonth'])->name('dashboard.filterMonth');

    // === Master Barang ===
    Route::prefix('barang')->name('barang.')->group(function () {
        Route::get('/', [MasterBarangController::class, 'index'])->name('index');
        Route::get('/create', [MasterBarangController::class, 'create'])->name('create');
        Route::post('/', [MasterBarangController::class, 'store'])->name('store');
        Route::delete('/bulk-delete', [MasterBarangController::class, 'bulkDelete'])->name('bulk-delete');
        Route::get('/{id}/edit', [MasterBarangController::class, 'edit'])->name('edit');
        Route::put('/{id}', [MasterBarangController::class, 'update'])->name('update');
        Route::delete('/{id}', [MasterBarangController::class, 'destroy'])->name('destroy');
    });

    // === Master Pegawai ===
    Route::prefix('pegawai')->name('pegawai.')->group(function () {
        Route::get('/', [PegawaiController::class, 'index'])->name('index');
        Route::get('/create', [PegawaiController::class, 'create'])->name('create');
        Route::post('/', [PegawaiController::class, 'store'])->name('store');
        Route::delete('/bulk-delete', [PegawaiController::class, 'bulkDelete'])->name('bulk-delete');
        Route::get('/{pegawai}/edit', [PegawaiController::class, 'edit'])->name('edit');
        Route::put('/{pegawai}', [PegawaiController::class, 'update'])->name('update');
        Route::delete('/{pegawai}', [PegawaiController::class, 'destroy'])->name('destroy');
    });

    // === Divisi ===
    Route::post('/divisi', [DivisiController::class, 'store'])->name('divisi.store');
    Route::put('/divisi/{division}', [DivisiController::class, 'update'])->name('divisi.update');
    Route::delete('/divisi/{division}', [DivisiController::class, 'destroy'])->name('divisi.destroy');
});