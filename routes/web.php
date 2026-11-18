<?php

use App\Http\Controllers\UndanganPublicController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DemoTemaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::view('/', 'home');

// halaman undangan publik, {slug} bagian dinamis yang isinya beda tiap undangan
// contoh: /u/rian-vina-64f2a3
Route::get('/u/{slug}', [UndanganPublicController::class, 'show'])->name('undangan.show');

//halman demo tema (kiri web , kanan hp)
Route::get('/demo/{tema}', [DemoTemaController::class, 'show'])->name('demo.show');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
