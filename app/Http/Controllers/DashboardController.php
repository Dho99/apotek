<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Resep;
use App\Models\Produk;
use App\Models\Keuangan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Events\UserNotification;

class DashboardController extends Controller
{
    public function apotekerIndex()
    {
        $data = Resep::where('isProceedByApoteker', '1')
            ->orderBy('created_at', 'desc')
            ->get()
            ->take(3);
        $dataNotProceed = Resep::where('isProceedByApoteker', '0')
            ->with('pasien', 'dokter')
            ->orderBy('created_at', 'desc')
            ->get()
            ->take(2);
        $decodedData = [];


        if (isset($data)) {
            foreach ($data as $item) {
                $dataProduk = Produk::whereIn('id', json_decode($item['obat_id']))
                    ->pluck('namaProduk')
                    ->toArray();
                $dataUser = User::all();
                $bornAt = Carbon::parse(
                    $dataUser
                        ->where('id', $item->pasien_id)
                        ->pluck('tanggal_lahir')
                        ->first(),
                )->format('Y');
                $now = Carbon::now()->format('Y');
                $age = $now - $bornAt;

                $decodedData[] = [
                    'id' => $item->id,
                    'kode' => $item->kode,
                    'namaProduk' => $dataProduk,
                    'pasien_id' => $dataUser
                        ->where('id', $item->pasien_id)
                        ->pluck('nama')
                        ->first(),
                    'gejala' => $item->gejala,
                    'jumlah' => json_decode($item->jumlah, true),
                    'dokter_id' => $dataUser->where('id', $item->dokter_id)->get('nama', 'kategoriDokter'),
                    'umur' => $age,
                    'catatan' => json_decode($item->catatan),
                    'isProceed' => $item->isProceed,
                    'isProceedByApoteker' => $item->isProceedByApoteker,
                    'satuan' => json_decode($item->satuan),
                    'created_at' => $item->created_at,
                ];
            }
        }

        return view('apoteker.dashboard', [
            'title' => 'Dashboard',
            'dataProcessed' => $decodedData,
            'dataNotProceed' => $dataNotProceed,
            'isPresent' => User::where(['level' => 0, 'isPresent' => 1])->get(),
            'obat' => Produk::all(),
            'kas' => Keuangan::orderBy('created_at', 'desc')
                ->pluck('saldo')
                ->first(),
        ]);
    }
}
