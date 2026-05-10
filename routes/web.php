<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardPasteController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\PasteController;
use Illuminate\Support\Facades\Route;

Route::post('/locale/{locale}', [LocaleController::class, 'update'])->name('locale.update');

Route::get('/', [PasteController::class, 'create'])->name('home');
Route::post('/pastes', [PasteController::class, 'store'])
    ->middleware('throttle:guest-pastes')
    ->name('pastes.store');
Route::get('/pastes/{paste}/created', [PasteController::class, 'created'])->name('pastes.created');
Route::get('/p/{slug}', [PasteController::class, 'show'])->name('pastes.show');
Route::post('/p/{slug}/password', [PasteController::class, 'verifyPassword'])->name('pastes.password.verify');
Route::delete('/guest-pastes/{slug}/{token}', [PasteController::class, 'destroyGuest'])->name('guest-pastes.destroy');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardPasteController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/pastes', [DashboardPasteController::class, 'index'])->name('dashboard.pastes.index');
    Route::get('/dashboard/pastes/{paste}', [DashboardPasteController::class, 'show'])->name('dashboard.pastes.show');
    Route::get('/dashboard/pastes/{paste}/edit', [DashboardPasteController::class, 'edit'])->name('dashboard.pastes.edit');
    Route::put('/dashboard/pastes/{paste}', [DashboardPasteController::class, 'update'])->name('dashboard.pastes.update');
    Route::delete('/dashboard/pastes/{paste}', [DashboardPasteController::class, 'destroy'])->name('dashboard.pastes.destroy');
});
