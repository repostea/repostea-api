<?php

use App\Http\Controllers\Admin\Auth\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('admin/login', [AuthController::class, 'login']);
});

Route::post('admin/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('admin.logout');

Route::prefix('admin')->name('admin.')->middleware(['admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
});
