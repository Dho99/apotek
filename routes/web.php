<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ResepController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PenjualanController;



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

Route::controller(AuthController::class)->group(function () {
    Route::get('/', 'login')->name('login');
    Route::post('/login', 'authenticate');
    Route::get('/logout', 'logout');
});


Route::controller(DashboardController::class)->group(function() {
    Route::middleware(['auth', 'user-access:Apoteker'])->group(function () {
        Route::get('/apoteker/dashboard', 'apotekerIndex');
    });

    Route::middleware(['auth', 'user-access:Dokter'])->group(function () {
        Route::get('/dokter/dashboard', 'dokterIndex');
    });

    Route::middleware(['auth', 'user-access:Kasir'])->group(function () {
        Route::get('/kasir/dashboard', 'kasirIndex');
    });
});

Route::controller(ProdukController::class)->group(function() {
    Route::get('/{filter}', 'filterProduk');
    Route::middleware(['auth', 'user-access:Apoteker'])->group(function () {
        Route::post('/apoteker/obat/create/store/', 'store');
        Route::get('/apoteker/obat/list/delete/{kode}', 'destroy');
        Route::get('/apoteker/obat/list', 'apotekerIndex');
        Route::get('/apoteker/obat/stock-in', 'apotekerStockIn');
        Route::get('/apoteker/obat/create', 'apotekerCreate');
        Route::get('/apoteker/obat/show/{kode}', 'apotekerShow');
        Route::post('/apoteker/obat/edit/{kode}', 'apotekerUpdate');
        Route::get('/apoteker/obat/add/stock/{kode}', 'apotekerAddStock');
        Route::post('/apoteker/obat/add/update/stock/{kode}', 'apotekerUpdateStock');

    });

    // Route::middleware(['auth', 'user-access:Dokter'])->group(function () {
    //     Route::get('/dokter/dashboard', 'dokterIndex');
    // });

    Route::middleware(['auth', 'user-access:Kasir'])->group(function () {
        Route::get('/kasir/dashboard', 'kasirIndex');
    });
});

Route::controller(SupplierController::class)->group(function() {
    Route::middleware(['auth', 'user-access:Apoteker'])->group(function () {
        Route::get('/apoteker/pemasok/get', 'apotekerGetSupplier');
        Route::get('/apoteker/pemasok/list', 'apotekerIndex');
        Route::get('/apoteker/pemasok/get/{kode}', 'apotekerFindSupplier');
        Route::post('/apoteker/pemasok/update/{kode}', 'apotekerUpdateSupplier');
        Route::post('/apoteker/pemasok/create/', 'apotekerCreateSupplier');
        Route::get('/apoteker/pemasok/delete/{kode}', 'apotekerDeleteSupplier');
    });

    // Route::middleware(['auth', 'user-access:Dokter'])->group(function () {
    //     Route::get('/dokter/dashboard', 'dokterIndex');
    // });

    Route::middleware(['auth', 'user-access:Kasir'])->group(function () {
        Route::get('/kasir/dashboard', 'kasirIndex');
    });
});

Route::controller(ResepController::class)->group(function() {
    Route::middleware(['auth', 'user-access:Apoteker'])->group(function () {
        Route::get('/apoteker/resep/antrian', 'apotekerIndex');
        Route::get('/apoteker/resep/katalog/filter/{satuan}', 'apotekerIndexFilter');
        Route::post('/apoteker/resep/proses/{kode}', 'apotekerProsesResep');

    });
    Route::middleware(['auth', 'user-access:Dokter'])->group(function () {
        Route::get('/dokter/resep/create', 'dokterCreate');
        Route::get('/dokter/resep/create/getAllResep', 'getResep');
        Route::post('/dokter/resep/create/new', 'dokterStore');
    });
});

Route::controller(UserController::class)->group(function() {
    Route::middleware(['auth', 'user-access:Apoteker'])->group(function () {
        // Manage dokter
        // Route::get('/apoteker/dokter/get', 'apotekerGetAllDokterAjax');
        // Route::get('/apoteker/dokter/get/{kategori}', 'apotekerFilterDokterByCategory');
        // Route::get('/apoteker/dokter/list', 'apotekerIndex');
        // Route::get('/apoteker/dokter/{kode}', 'apotekerShowDokter');



        // Manage pasien
        Route::get('/apoteker/user/list', 'apotekerListPasien');
        Route::get('/apoteker/user/get/{level}', 'apotekerGetUserByLevel');
        Route::get('/apoteker/user/show/get/{kode}', 'apotekerGetUser');
        Route::post('/apoteker/user/update/', 'apotekerUpdateUser');


        Route::get('/apoteker/account/manage/{kode}', 'apotekerShow');
        Route::get('/apoteker/account/edit/{kode}', 'apotekerEdit');
        Route::post('/apoteker/account/update/{kode}', 'apotekerUpdate');
        Route::get('/apoteker/user/delete/{kode}', 'apotekerDeleteUser');
// Route for Dokter Data




    });



    Route::middleware(['auth', 'user-access:Kasir'])->group(function () {
        Route::get('/kasir/dashboard', 'kasirIndex');
    });
});


Route::controller(PenjualanController::class)->group(function() {
    Route::middleware(['auth','user-access:Apoteker'])->group(function () {
        Route::post('/apoteker/resep/antrian/proses/', 'store');
        Route::get('/apoteker/laporan/penjualan', 'index');
        Route::get('/apoteker/laporan/penjualan/get/{year}', 'getDataPenjualan');
        Route::get('/apoteker/laporan/penjualan/inovice/{kode}', 'apotekerGetInvoice');
    });
});


