<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Resep;
use App\Models\Dokter;
use App\Models\Pasien;
use App\Models\Produk;
use App\Models\Keuangan;
use App\Models\Kunjungan;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
// use App\Events\UserNotification;

class DashboardController extends Controller
{

    private $title = 'Dashboard';
    private $localeDate;

    public function __construct(){
        $this->localeDate = Carbon::now()->locale('id_ID');
    }

    public function apotekerIndex()
    {
        $patients = Pasien::getAll();
        $doctors = Dokter::getAll();
        $kunjunganToday = Kunjungan::today();

        return view('administrator.dashboard', [
            'title' => $this->title,
            'isPresent' => Dokter::isPresent(),
            'countKunjungan' => count($kunjunganToday),
            'countDokter' => $doctors,
            'countPatients' => count($patients),
            'dateOfDay' => $this->localeDate.', '.$this->localeDate->format('d').' '.$this->localeDate->monthName.' '.$this->localeDate->format('Y'),
            'thisYear' => $this->localeDate,
            'countTransaction' => Penjualan::count(),
            'calculateTransaction' => Penjualan::whereYear('created_at', now()->format('Y'))->get()->sum()
        ]);
    }

    public function kasirIndex(){
        return view('kasir.dashboard',[
            'title' => $this->title,
            'countTransaction' => Penjualan::whereYear('created_at', now()->format('Y'))->count(),
            'calculateTransaction' => Penjualan::whereYear('created_at', now()->format('Y'))->get()->sum(),
            'thisYear' => $this->localeDate,
            'totalProduct' => Produk::all()->count(),
            'expiredProduct' => Produk::where('expDate','<=',now())->count()
        ]);
    }

}
