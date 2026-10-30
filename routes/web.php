<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InfaqController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\InfaqMassalController;
use App\Http\Controllers\SiswaSyncController;

/*
|--------------------------------------------------------------------------
| Landing page
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
        return view('welcome');
        })->name('welcome');


/*
|--------------------------------------------------------------------------
| Login
|--------------------------------------------------------------------------
*/

Route::get('/login', [LoginController::class, 'showLoginForm'])
    ->name('login');

Route::post('/login', [LoginController::class, 'login'])
    ->name('login.submit');


/*
|--------------------------------------------------------------------------
| Register
|--------------------------------------------------------------------------
*/

Route::get('/register', [RegisterController::class, 'show'])
    ->name('register');

Route::post('/register', [RegisterController::class, 'register'])
    ->name('register.store');


/*
|--------------------------------------------------------------------------
| Logout
|--------------------------------------------------------------------------
*/

Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout');


/*
|--------------------------------------------------------------------------
| Halaman yang Membutuhkan Login
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | Data Infaq
    |--------------------------------------------------------------------------
    */

    Route::get('/data-infaq', [InfaqController::class, 'index'])
        ->name('infaq.index');

    Route::post('/data-infaq', [InfaqController::class, 'store'])
        ->name('infaq.store');

    Route::put('/data-infaq/{infaq}', [InfaqController::class, 'update'])
        ->name('infaq.update');

    Route::delete('/data-infaq/{infaq}', [InfaqController::class, 'destroy'])
        ->name('infaq.destroy');


    /*
    |--------------------------------------------------------------------------
    | Data Pengeluaran
    |--------------------------------------------------------------------------
    */

    Route::get('/data-pengeluaran', [PengeluaranController::class, 'index'])
        ->name('pengeluaran.index');

    Route::post('/data-pengeluaran', [PengeluaranController::class, 'store'])
        ->name('pengeluaran.store');

    Route::put('/data-pengeluaran/{pengeluaran}', [PengeluaranController::class, 'update'])
        ->name('pengeluaran.update');

    Route::delete('/data-pengeluaran/{pengeluaran}', [PengeluaranController::class, 'destroy'])
        ->name('pengeluaran.destroy');


    /*
    |--------------------------------------------------------------------------
    | Laporan
    |--------------------------------------------------------------------------
    */

    Route::get('/laporan', [LaporanController::class, 'index'])
        ->name('laporan.index');

    Route::get('/laporan/cetak', [LaporanController::class, 'cetak'])
        ->name('laporan.cetak');


    /*
    |--------------------------------------------------------------------------
    | Profil
    |--------------------------------------------------------------------------
    */

    Route::get('/profil', [ProfilController::class, 'index'])
        ->name('profil.index');

    Route::put('/profil', [ProfilController::class, 'update'])
        ->name('profil.update');

    Route::put('/profil/ganti-akun', [ProfilController::class, 'gantiAkun'])
        ->name('profil.ganti-akun');
    Route::get('/infaq-massal', [
        InfaqMassalController::class,
        'index'
         ])->name('infaq.massal');

    Route::get('/infaq-massal/siswa', [
        InfaqMassalController::class,
        'siswa'
        ])->name('infaq.massal.siswa');

    Route::post('/infaq-massal', [
        InfaqMassalController::class,
        'store'
        ])->name('infaq.massal.store');
        
    // Import Data Siswa dari CSV
    Route::post('/siswa/import', [SiswaController::class, 'import'])
         ->name('siswa.import');
    Route::post('/siswa/sinkronisasi', [SiswaSyncController::class, 'sync'])
         ->name('siswa.sync');

});