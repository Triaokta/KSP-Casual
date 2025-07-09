<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Rute untuk halaman utama, otomatis mengarahkan ke login atau daftar karyawan
Route::get('/', function () {
    return auth()->check() ? redirect()->route('employees.index') : redirect()->route('login');
});

// Grup rute yang hanya bisa diakses setelah login
Route::middleware(['auth'])->group(function () {

    // Rute 'home' dan 'dashboard' untuk mengatasi error dari template lama
    Route::get('/home', fn() => redirect()->route('employees.index'))->name('home');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Rute untuk fitur Import Excel
    Route::get('/employees/import', [EmployeeController::class, 'importForm'])->name('employees.import.form');
    Route::post('/employees/import', [EmployeeController::class, 'importStore'])->name('employees.import.store');
    Route::get('/employees/template', [EmployeeController::class, 'template'])->name('employees.template');

    // Rute untuk mengubah status aktif/nonaktif
    Route::post('/employees/{employee}/toggle-status', [EmployeeController::class, 'toggleStatus'])->name('employees.toggle_status');
    
    // Rute resource untuk semua fungsi CRUD (Create, Read, Update, Delete)
    // Penting: Letakkan ini di paling bawah agar tidak tertimpa rute custom di atas.
    Route::resource('employees', EmployeeController::class);

});
