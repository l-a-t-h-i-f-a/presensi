<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\IzinController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\PresensiController;

Route::get('/', function () {
    return view('welcomepage');
});

// Ini dashboard untuk user biasa
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Ini dashboard untuk admin
Route::get('/dashboard-absensi', function () {
    return view('dashboard-absensi');
})->middleware(['auth', 'verified'])->name('dashboard-absensi');

// Route umum (akses user biasa & admin)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ⬇️ Izin bisa diajukan oleh user biasa
    Route::resource('/izin', IzinController::class)->except(['show']);
    Route::resource('/presensi', PresensiController::class)->except(['show']);
    Route::post('/presensi/{id}/pulang', [PresensiController::class, 'pulang']);
     
});

// Route khusus admin
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('/pegawai', PegawaiController::class)->except(['show']);
    Route::resource('/role', RoleController::class);
    Route::resource('/lokasi', LokasiController::class);
    Route::put('/lokasi/{id}/aktifkan', [LokasiController::class, 'aktifkan']);
    Route::put('/lokasi/{id}/nonaktifkan', [LokasiController::class, 'nonaktifkan']);
    Route::resource('/shift', ShiftController::class);

    Route::get('/presensi/export/{role?}', [PresensiController::class, 'export'])->name('presensi.export');
    Route::get('/izin/export/{role?}', [IzinController::class, 'export'])->name('izin.export');
    Route::get('/pegawai/export/{role?}', [PegawaiController::class, 'export'])->name('pegawai.export');
   
});

require __DIR__.'/auth.php';
