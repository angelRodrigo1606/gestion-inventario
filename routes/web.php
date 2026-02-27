<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EstadisticaController;
use App\Http\Controllers\IngresoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\SalidaController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect('/dashboard'));

Route::middleware('guest')->group(function () {
    Route::get('/login',    [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login',   [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout',      [LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard',    [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('productos', ProductoController::class)->except(['show']);
    Route::get('/estadisticas', [EstadisticaController::class, 'index'])->name('estadisticas.index');
    Route::get('/ingresos',     [IngresoController::class, 'index'])->name('ingresos.index');
    Route::get('/salidas',      [SalidaController::class, 'index'])->name('salidas.index');
    Route::get('/agenda',       [AgendaController::class, 'index'])->name('agenda.index');
});
