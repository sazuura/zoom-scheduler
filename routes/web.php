<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\PeralatanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\PenjadwalanController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    if (auth()->check()) {
        return auth()->user()->role === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('operator.dashboard');
    }
    return redirect()->route('login');
})->middleware(['auth'])->name('dashboard');

Route::prefix('admin')->name('admin.')->middleware(['auth','role:admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::resource('/peralatan', PeralatanController::class)->names('peralatan');
    Route::resource('/users', UserController::class)->names('users'); 
    Route::resource('/jadwal', PenjadwalanController::class)->names('jadwal');
    Route::resource('/absensi', AdminController::class)->names('absensi');
    Route::post('/absensi/{id}/{status}', [AdminController::class, 'updateStatus'])->name('absensi.updateStatus');
    Route::get('/laporan', [AdminController::class, 'laporan'])->name('laporan.index');
    Route::get('/laporan/export/pdf', [AdminController::class, 'exportPdf'])->name('laporan.exportPdf');
    Route::get('/laporan/export/excel', [AdminController::class, 'exportExcel'])->name('laporan.exportExcel');
});

Route::prefix('operator')->name('operator.')->middleware(['auth','role:operator'])->group(function () {
    Route::get('/dashboard', [OperatorController::class, 'dashboard'])->name('dashboard');
    Route::get('/jadwal', [OperatorController::class, 'jadwal'])->name('jadwal.index');
    Route::get('/absensi', [OperatorController::class, 'absensi'])->name('absensi.index');
    Route::post('/absensi', [OperatorController::class, 'absensiStore'])->name('absensi.store');
    Route::delete('/absensi/{id}', [OperatorController::class, 'absensiCancel'])->name('absensi.cancel');
    Route::get('/peralatan', [OperatorController::class, 'peralatan'])->name('peralatan.index');
});
require __DIR__.'/auth.php';