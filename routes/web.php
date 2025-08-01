<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Rute untuk halaman utama, otomatis mengarahkan ke login atau dashboard.
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Grup rute yang hanya bisa diakses setelah login.
Route::middleware(['auth'])->group(function () {

    // Rute untuk halaman Dashboard.
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // --- Rute Custom untuk Karyawan ---
    // Semua rute spesifik diletakkan sebelum Route::resource

    // Rute untuk fitur Import Excel
    Route::get('/import-karyawan', [EmployeeController::class, 'importForm'])->name('import.karyawan.form');
    Route::post('/import-karyawan', [EmployeeController::class, 'importStore'])->name('import.karyawan.store');
    Route::get('/import-karyawan/template', [EmployeeController::class, 'downloadTemplate'])->name('import.karyawan.template');


    // Rute untuk fitur Export Excel
    Route::get('/employees/export', [EmployeeController::class, 'export'])->name('employees.export');

    // Rute untuk mengubah status aktif/nonaktif
    Route::post('/employees/{employee}/toggle-status', [EmployeeController::class, 'toggleStatus'])->name('employees.toggle_status');
    
    // Rute resource untuk semua fungsi CRUD standar (index, create, show, edit, dll.)
    // Diletakkan di paling bawah grup karyawan.
    Route::resource('employees', EmployeeController::class);

});

