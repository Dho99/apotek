<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Resep;
use App\Models\Dokter;
use App\Models\Pasien;
use App\Models\Produk;
use App\Models\Keuangan;
use App\Models\Kunjungan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
// use App\Events\UserNotification;

class DashboardController extends Controller
{
    public function apotekerIndex()
    {
        $patients = Pasien::getAll();
        $doctors = Dokter::getAll();
        $kunjunganToday = Kunjungan::today();
        $localeDate =  Carbon::now()->locale('id_ID');

        return view('apoteker.dashboard', [
            'title' => 'Dashboard',
            'isPresent' => Dokter::isPresent(),
            'countKunjungan' => count($kunjunganToday),
            'countDokter' => $doctors,
            'countPatients' => count($patients),
            'dateOfDay' => $localeDate->dayName.', '.$localeDate->format('d').' '.$localeDate->monthName.' '.$localeDate->format('Y'),
            'thisYear' => $localeDate
        ]);
    }


    public function countProduct(Request $request){
        if($request->ajax()){
            $obat = Produk::all();
            return response()->json(['obat' => $obat]);
        }else{
            return response(400);
        }
    }

    public function countKas(Request $request){
        if($request->ajax()){
            $kas = Keuangan::orderBy('created_at', 'desc')->pluck('saldo')->first();
            return response()->json(['kas' => $kas]);
        }else{
            return response(400);
        }
    }


}
