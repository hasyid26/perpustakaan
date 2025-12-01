<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PeminjamanUserController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Auth Routes
require __DIR__.'/auth.php';

// Protected Routes
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /* ------------------------------------------------------------------
        ROUTES UNTUK ADMIN & PETUGAS
    ------------------------------------------------------------------ */
    Route::middleware(['role:administrator,petugas'])->group(function () {

        // Manajemen Buku (CRUD penuh)
        Route::resource('buku', BukuController::class);

        // Manajemen Peminjaman
        Route::get('peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
        Route::get('peminjaman/{peminjaman}', [PeminjamanController::class, 'show'])->name('peminjaman.show');

        // Approve / Reject Request Peminjaman
        Route::post('peminjaman/{peminjaman}/approve', [PeminjamanController::class, 'approvePeminjaman'])
            ->name('peminjaman.approve-peminjaman');
        Route::post('peminjaman/{peminjaman}/reject', [PeminjamanController::class, 'rejectPeminjaman'])
            ->name('peminjaman.reject-peminjaman');

        // Approve / Reject Pengembalian
        Route::post('peminjaman/{peminjaman}/approve-pengembalian', [PeminjamanController::class, 'approvePengembalian'])
            ->name('peminjaman.approve-pengembalian');
        Route::post('peminjaman/{peminjaman}/reject-pengembalian', [PeminjamanController::class, 'rejectPengembalian'])
            ->name('peminjaman.reject-pengembalian');

        /* ========================
           ROUTES LAPORAN
           HANYA ADMIN & PETUGAS
        ========================= */
        // List semua laporan
        Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('laporan/export-pdf', [LaporanController::class, 'exportPdf'])->name('laporan.export-pdf');

        // Detail laporan per peminjaman
        Route::get('laporan/{peminjaman}', [LaporanController::class, 'show'])->name('laporan.show');

        // Filter / Laporan spesifik
        Route::post('laporan/peminjaman', [LaporanController::class, 'peminjaman'])->name('laporan.peminjaman');
        Route::get('laporan/buku', [LaporanController::class, 'buku'])->name('laporan.buku');
        Route::get('laporan/anggota', [LaporanController::class, 'anggota'])->name('laporan.anggota');
    });

    /* ------------------------------------------------------------------
        ROUTES UNTUK ADMIN SAJA
    ------------------------------------------------------------------ */
    Route::middleware(['role:administrator'])->group(function () {
        Route::resource('users', UserController::class);
    });

    /* ------------------------------------------------------------------
        ROUTES UNTUK PEMINJAM
    ------------------------------------------------------------------ */
    Route::middleware(['role:peminjam'])->group(function () {

        // Lihat koleksi buku (read-only)
        Route::get('koleksi-buku', [BukuController::class, 'koleksi'])->name('koleksi.buku');

        // Peminjaman User
        Route::get('peminjaman-user', [PeminjamanUserController::class, 'index'])->name('peminjaman-user.index');
        Route::get('peminjaman-user/create', [PeminjamanUserController::class, 'create'])->name('peminjaman-user.create');
        Route::post('peminjaman-user', [PeminjamanUserController::class, 'store'])->name('peminjaman-user.store');
        Route::get('peminjaman-user/{peminjaman}', [PeminjamanUserController::class, 'show'])->name('peminjaman-user.show');

        // Request & Cancel Pengembalian
        Route::post('peminjaman-user/{peminjaman}/request-pengembalian', [PeminjamanUserController::class, 'requestPengembalian'])
            ->name('peminjaman-user.request');
        Route::post('peminjaman-user/{peminjaman}/cancel-request', [PeminjamanUserController::class, 'cancelRequest'])
            ->name('peminjaman-user.cancel-request');
    });
});
