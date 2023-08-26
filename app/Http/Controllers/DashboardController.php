<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resep;
use App\Models\Produk;
use App\Models\User;

class DashboardController extends Controller
{
    public function apotekerIndex()
    {
        $data = Resep::all();
        $decodedData = [];

        foreach ($data as $item) {
            $namaProduk = Produk::whereIn('id', json_decode($item['obat_id']))->pluck('namaProduk')->toArray();
            $decodedData[] = [
                'id' => $item->id,
                'kode' => $item->kode,
                'namaProduk' => $namaProduk,
                'pasien_id' => $item->pasien_id,
                'gejala' => $item->gejala,
                'jumlah' => json_decode($item->jumlah, true),
                'dokter_id' => $item->dokter_id,
                'umur' => $item->umur,
                'catatan' => json_decode($item->catatan, true),
                'isProceed' => $item->isProceed,
                'isProceedByApoteker' => $item->isProceedByApoteker,
                'satuan' => json_decode($item->satuan, true),
                'created_at' => $item->created_at->format('H:i'),
            ];
        }

        return view('apoteker.dashboard', [
            'title' => 'Dashboard',
            'resepMasuk' => $decodedData,
            'data' => $data,
            'isPresent' => User::where(['level' => 0, 'isPresent' => 1])->get(),
            'obat' => Produk::all(),
        ]);
    }

    public function dokterIndex()
    {
        return view('dokter.dashboard', [
            'title' => 'Dashboard',
        ]);
    }

    public function kasirIndex()
    {
        return view('kasir.dashboard', [
            'title' => 'Dashboard',
        ]);
    }
}
