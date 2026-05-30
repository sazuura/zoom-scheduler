<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\InventarisController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PeralatanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PenjadwalanController;

Route::get('/', fn() => redirect()->route('login'));

Route::get('/dashboard', function () {
    if (auth()->check()) {
        return match (auth()->user()->role) {
            'admin'      => redirect()->route('admin.dashboard'),
            'inventaris' => redirect()->route('inventaris.dashboard'),
            default      => redirect()->route('operator.dashboard'),
        };
    }
    return redirect()->route('login');
})->middleware(['auth'])->name('dashboard');

// ─── Admin ────────────────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::resource('/peralatan', PeralatanController::class)->names('peralatan');
    Route::resource('/users',     UserController::class)->names('users');
    Route::resource('/jadwal',    PenjadwalanController::class)->names('jadwal');

    Route::get('/absensi',              [AdminController::class, 'index'])->name('absensi.index');
    Route::get('/absensi/{id}',         [AdminController::class, 'show'])->name('absensi.show');
    Route::post('/absensi/{id}/status', [AdminController::class, 'updateStatus'])->name('absensi.updateStatus');

    Route::get('/laporan',              [AdminController::class, 'laporan'])->name('laporan.index');
    Route::get('/laporan/export/pdf',   [AdminController::class, 'exportPdf'])->name('laporan.exportPdf');
    Route::get('/laporan/export/excel', [AdminController::class, 'exportExcel'])->name('laporan.exportExcel');
});

// ─── Operator ─────────────────────────────────────────────────────────────────
Route::prefix('operator')->name('operator.')->middleware(['auth', 'role:operator'])->group(function () {
    Route::get('/dashboard',        [OperatorController::class, 'dashboard'])->name('dashboard');
    Route::get('/jadwal',           [OperatorController::class, 'jadwal'])->name('jadwal.index');
    Route::get('/absensi',          [OperatorController::class, 'absensi'])->name('absensi.index');
    Route::post('/absensi',         [OperatorController::class, 'absensiStore'])->name('absensi.store');
    Route::delete('/absensi/{id}',  [OperatorController::class, 'absensiCancel'])->name('absensi.cancel');
    Route::get('/peralatan',        [OperatorController::class, 'peralatan'])->name('peralatan.index');
    Route::patch('/peralatan/{id}', [OperatorController::class, 'peralatanUpdate'])->name('peralatan.update');

    // Peminjaman (Fase 3)
    Route::get('/peminjaman',        [PeminjamanController::class, 'operatorIndex'])->name('peminjaman.index');
    Route::get('/peminjaman/create', [PeminjamanController::class, 'operatorCreate'])->name('peminjaman.create');
    Route::post('/peminjaman',       [PeminjamanController::class, 'operatorStore'])->name('peminjaman.store');
});

// ─── Inventaris ───────────────────────────────────────────────────────────────
Route::prefix('inventaris')->name('inventaris.')->middleware(['auth', 'role:inventaris'])->group(function () {
    Route::get('/dashboard',  [InventarisController::class, 'dashboard'])->name('dashboard');
    Route::get('/peralatan',  [InventarisController::class, 'peralatanIndex'])->name('peralatan.index');

    // Peminjaman (Fase 3)
    Route::get('/peminjaman',                         [PeminjamanController::class, 'inventarisIndex'])->name('peminjaman.index');
    Route::post('/peminjaman/{id}/approve',           [PeminjamanController::class, 'inventarisApprove'])->name('peminjaman.approve');
    Route::post('/peminjaman/{id}/reject',            [PeminjamanController::class, 'inventarisReject'])->name('peminjaman.reject');
    Route::post('/peminjaman/{id}/kembali',           [PeminjamanController::class, 'inventarisKembali'])->name('peminjaman.kembali');
});

require __DIR__ . '/auth.php';
