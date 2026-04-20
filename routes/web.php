<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController; // ← tambahkan ini

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Profile - semua user yang login
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/dashboard/data', [DashboardController::class, 'getDashboardData'])->name('dashboard.data');
});

// Hanya Admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('services', ServiceController::class);
    Route::get('/members/export-pdf', [MemberController::class, 'exportPdf'])->name('members.export-pdf');
    Route::resource('members', MemberController::class);
});

// Admin & Kasir
Route::middleware(['auth', 'role:admin,kasir'])->group(function () {
    Route::get('/customers/export-pdf', [CustomerController::class, 'exportPdf'])->name('customers.export-pdf');
    Route::resource('customers', CustomerController::class);
    Route::get('/transactions/pdf', [TransactionController::class, 'Pdf'])->name('transactions.pdf');
    Route::get('/transactions/{transaction}/struk', [TransactionController::class, 'struk'])->name('transactions.struk'); // ← tambahkan di sini
    Route::resource('transactions', TransactionController::class);
});

require __DIR__.'/auth.php';