<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\ResepController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DropzoneController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\KeuanganController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KunjunganController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\AdministratorController;
use App\Http\Controllers\DashboardDokterController;
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


Route::prefix('dokter')->middleware(['auth','user-access:Dokter'])->group(function(){
    Route::controller(DashboardDokterController::class)->group(function(){
        Route::get('/dashboard','index');

    });
});
Route::prefix('administrator')->middleware(['auth', 'user-access:Administrator'])->group(function () {
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/dashboard', 'apotekerIndex');
        Route::get('/count/produk/dashboard','countProduct');
        Route::get('/count/kas/dashboard','countKas');
    });

    Route::controller(DokterController::class)->group(function(){
       Route::put('/update/practice/time', 'updatePracticeTime')->name('updatePracticeTime');
    });

    Route::prefix('manage')->group(function(){
        Route::resource('dokter', DokterController::class)->names('dokter');
        Route::resource('administrator', AdministratorController::class)->names('administrator');
        Route::resource('pasien', PasienController::class)->names('pasien');
        Route::resource('kasir', KasirController::class)->names('kasir');
    });

    Route::resource('kunjungan', KunjunganController::class)->names('kunjungan');

    // Route::post('upload/product/images',[ProductController::class, 'uploadImages'])->name('uploadProductImages');
    Route::resource('products', ProductController::class)->names('products');

    Route::controller(ReportController::class)->prefix('laporan')->group(function(){
        Route::get('/kunjungan','laporanKunjungan')->name('laporanKunjungan');
        Route::get('/filter','filter')->name('filterKunjungan');
        Route::get('/penjualan','laporanPenjualan')->name('laporanPenjualan');

    });

    Route::post('kategori/create',[KategoriController::class,'updateKategori'])->name('updateKategori');
    Route::resource('kategori',KategoriController::class)->names('kategori');
    Route::resource('supplier',SupplierController::class)->names('supplier');
});

Route::prefix('kasir')->group(function(){
    Route::get('/dashboard', [DashboardController::class, 'kasirIndex']);
    Route::get('/products/expired',[ProductController::class, 'getExpiredProduct'])->name('products.kadaluarsa');
    Route::resource('products', ProductController::class)->only([
        'index','show'
    ]);
});

Route::controller(DropzoneController::class)->prefix('file')->group(function(){
    Route::post('upload/{path}','uploadFile')->name('uploadFile');
    Route::post('delete','deleteFile')->name('deleteFile');
});

// Route::controller(ProdukController::class)->group(function () {
//     Route::get('/obat/filter/{filter}', 'filterProduk');
//     Route::get('/obat/description/{kode}', 'showDescription');
//     Route::middleware(['auth', 'user-access:Apoteker'])->group(function () {
//         Route::post('/apoteker/obat/create/store/', 'store');
//         Route::get('/apoteker/obat/list/delete/{kode}', 'destroy');
//         Route::get('/apoteker/obat/list', 'apotekerIndex');
//         Route::get('/apoteker/obat/stock-in', 'apotekerStockIn');
//         Route::get('/apoteker/obat/create', 'apotekerCreate');
//         Route::get('/apoteker/obat/show/{kode}', 'apotekerShow');
//         Route::post('/apoteker/obat/edit/{kode}', 'apotekerUpdate');
//         Route::get('/apoteker/obat/add/stock/{kode}', 'apotekerAddStock');
//         Route::get('/apoteker/obat/get/{kode}', 'apotekerGetProdukByKode');
//         Route::post('/apoteker/obat/add/update/stock/{kode}', 'apotekerUpdateStock');
//         Route::get('/apoteker/obat/kadaluarsa', 'produkKadaluarsa');
//         Route::get('/apoteker/obat/kadaluarsa/filter', 'filterProdukKadaluarsa');
//     });

// });

// Route::controller(SupplierController::class)->group(function () {
//     Route::middleware(['auth', 'user-access:Apoteker'])->group(function () {
//         Route::get('/apoteker/pemasok/get', 'apotekerGetSupplier');
//         Route::get('/apoteker/pemasok/list', 'apotekerIndex');
//         Route::get('/apoteker/pemasok/get/{kode}', 'apotekerFindSupplier');
//         Route::post('/apoteker/pemasok/update', 'apotekerUpdateSupplier');
//         Route::get('/apoteker/pemasok/delete/{kode}', 'apotekerDeleteSupplier');
//     });
// });

// Route::controller(ResepController::class)->group(function () {
//     Route::get('/katalog/filter/{satuan}', 'apotekerIndexFilter');
//     Route::post('/resep/reject','rejectResep');
//     Route::post('/resep/confirm','confirmResep');
//     Route::get('/resep/get/{kode}', 'getResepByKode');

//     Route::middleware(['auth', 'user-access:Apoteker'])->group(function () {
//         Route::get('/apoteker/resep/not-processed', 'notProcessedResep');
//         Route::get('/apoteker/resep/antrian', 'apotekerIndex');
//         Route::get('/apoteker/resep/list', 'apotekerListResep');
//         Route::post('/apoteker/resep/proses/{kode}', 'apotekerProsesResep');
//         Route::get('/apoteker/resep/proses/transaksi/{kode}', 'apotekerGetResepByKode');
//         Route::get('/apoteker/transaksi/resep', 'kasirWithResep');
//         Route::post('/apoteker/transaksi/resep/create', 'kasirCreateResep');
//     });
//     Route::middleware(['auth', 'user-access:Dokter'])->group(function () {
//         Route::get('/dokter/resep/create', 'dokterCreate');
//         Route::get('/dokter/resep/list', 'dokterListResep');
//         Route::get('/dokter/resep/create/getAllResep', 'getResep');
//         Route::post('/dokter/resep/create/new', 'dokterStore');
//     });
// });


// Route::controller(UserController::class)->group(function () {
//     Route::get('/account/manage/{kode}', 'apotekerShow');
//     Route::get('/account/edit/{kode}', 'apotekerEdit');
//     Route::post('/account/update/{kode}', 'apotekerUpdate');

//     Route::middleware(['auth', 'user-access:Apoteker'])->group(function () {
//         Route::get('/apoteker/user/list', 'apotekerListPasien');
//         Route::get('/apoteker/user/get/{level}', 'apotekerGetUserByLevel');
//         Route::get('/apoteker/user/show/get/{kode}', 'apotekerGetUser');
//         Route::post('/apoteker/user/update/', 'apotekerUpdateUser');
//         Route::get('/apoteker/user/delete/{kode}', 'apotekerDeleteUser');
//         Route::get('/kasir/pasien/get/{kode}', 'kasirGetPasienByKode');
//     });

//     Route::middleware(['auth', 'user-access:Kasir'])->group(function(){
//         Route::get('/kasir/pasien/create', 'kasirCreatePasien');
//         Route::get('/kasir/pasien/list', 'kasirPasienList');
//         Route::get('/kasir/pasien/get', 'kasirGetPasien');
//         Route::get('/kasir/pasien/delete/{kode}', 'kasirDeletePasien');
//         Route::post('/kasir/pasien/update/', 'kasirUpdatePasien');
//     });


// });

// Route::controller(PenjualanController::class)->group(function () {
//     Route::post('/resep/antrian/proses', 'store');
//     Route::middleware(['auth', 'user-access:Apoteker'])->group(function () {
//         Route::get('/apoteker/laporan/penjualan', 'index');
//         Route::get('/apoteker/laporan/penjualan/get/{year}', 'getDataPenjualan');
//         Route::get('/apoteker/laporan/penjualan/inovice/{kode}', 'apotekerGetInvoice');
//         Route::get('/apoteker/laporan/penjualan/get', 'show');
//     });
// });

// Route::controller(KategoriController::class)->group(function(){
//     Route::middleware(['auth','user-access:Apoteker'])->group(function(){
//         Route::get('/obat/kategori', 'index');
//         Route::get('/obat/kategori/all', 'getAllKategories');
//         Route::get('/obat/kategori/get/{kategori}', 'show');
//         Route::post('/obat/kategori/update', 'updateKategories');
//         Route::get('/obat/kategori/delete/{golongan}', 'deleteKategori');
//     });
// });

// Route::controller(KeuanganController::class)->group(function(){
//     Route::middleware(['auth', 'user-access:Apoteker'])->group(function(){
//         Route::get('/apoteker/laporan/keuangan', 'index');
//         Route::get('/apoteker/laporan/keuangan/get/', 'getByKategori');
//         Route::post('/apoteker/laporan/keuangan/create', 'create');
//     });
// });
