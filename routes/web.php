<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\MasterBarangController;
use App\Http\Controllers\PegawaiController;

// ========== AUTH (LOGIN / REGISTER / LOGOUT) ==========
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ========== LUPA PASSWORD ==========
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');

// ========== SETELAH LOGIN ADMIN ==========
Route::middleware('auth:admin')->group(function () {
    // Dashboard setelah login
    Route::get('/', [AuthController::class, 'index'])->name('dashboard');

    // === Master Barang ===
    Route::prefix('barang')->group(function () {
        Route::get('/', [MasterBarangController::class, 'index'])->name('barang.index');
        Route::get('/create', [MasterBarangController::class, 'create'])->name('barang.create');
        Route::post('/', [MasterBarangController::class, 'store'])->name('barang.store');

        // Bulk delete barang
        Route::delete('/bulk-delete', [MasterBarangController::class, 'bulkDelete'])->name('barang.bulk-delete');

        // Edit/hapus barang
        Route::get('/{id}/edit', [MasterBarangController::class, 'edit'])->name('barang.edit');
        Route::put('/{id}', [MasterBarangController::class, 'update'])->name('barang.update');
        Route::delete('/{id}', [MasterBarangController::class, 'destroy'])->name('barang.destroy');
    });

    // === Master Pegawai ===
    Route::prefix('pegawai')->group(function () {
        Route::get('/', [PegawaiController::class, 'index'])->name('pegawai.index');
        Route::get('/create', [PegawaiController::class, 'create'])->name('pegawai.create');
        Route::post('/', [PegawaiController::class, 'store'])->name('pegawai.store');

        // Bulk delete pegawai
        Route::delete('/bulk-delete', [PegawaiController::class, 'bulkDelete'])->name('pegawai.bulk-delete');

        // Edit/hapus pegawai
        Route::get('/{id}/edit', [PegawaiController::class, 'edit'])->name('pegawai.edit');
        Route::put('/{id}', [PegawaiController::class, 'update'])->name('pegawai.update');
        Route::delete('/{id}', [PegawaiController::class, 'destroy'])->name('pegawai.destroy');
    });
});
