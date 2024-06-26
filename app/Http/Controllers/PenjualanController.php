<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Resep;
use App\Models\Produk;
use App\Models\Keuangan;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
// use App\Events\UserNotification;
use Illuminate\Support\Str;

class PenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('administrator.laporan.penjualan', [
            'title' => 'Laporan Penjualan',
            'yearOption' => Penjualan::orderBy('created_at','asc')->get()->groupBy(function($item){
                return Carbon::parse($item->created_at)->format('Y');
            }),
            'perBulan' => Penjualan::orderBy('created_at','asc')->get()->groupBy(function($item){
                return Carbon::parse($item->created_at)->format('F');
            }),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function getDataPenjualan(Request $request, $year)
    {
        if ($request->ajax()) {
            $data = Penjualan::orderBy('created_at','asc')->whereYear('created_at' , $year )->get(['jumlah', 'subtotal','created_at'])->groupBy(function ($date) {
                return Carbon::parse($date->created_at)->format('F');
            });
            return response()->json(['data' => $data]);
        } else {
            abort(400);
        }
    }



    public function store(Request $request)
    {
        if (request()->ajax()) {
            $dataPasien = User::where('nama', $request->pasienId)->pluck('id')->first();
            if(isset($dataPasien)){
                if (isset($request->pasienId)) {
                    $pasien_id = $dataPasien;
                }
                if(isset($request->dokterId)){
                    $dokter_id = User::where('nama', $request->dokterId)->pluck('id')->first();
                }else{
                    $dokter_id = 0;
                }

            }else{
                $newPasien = User::create([
                    'kode' => 'PSN-'.mt_rand(0000,9999),
                    'nama' => Str::title($request->pasienId),
                    'username' => Str::slug($request->pasienId),
                    'email' => fake()->unique()->safeEmail(),
                    'alamat' => fake()->address(),
                    'tanggal_lahir' => fake()->dateTimeThisDecade(),
                    'level' => '3',
                    'status' => 'Aktif'
                ]);

                $pasien_id = $newPasien->id;
                // $dokter_id = 0;
            }


            if($request->kategoriPenjualan === 'Resep'){
                $dataResep = Resep::where('kode', $request->kodeResep)->first();
                if(isset($dataResep)){
                    $dataResep->update([
                        'isProceedByApoteker' => '1',
                        'apoteker_id' => auth()->user()->id,
                        'updated_at' => Carbon::now(),
                        'isSuccess' => '1',
                    ]);

                    // event(new UserNotification('Apoteker telah selesai memproses Resep '.$request->kodeResep, auth()->user(), '/kasir/resep/konfirmasi', '2', 'Selesaikan Resep'));
                }else{
                    return response('Data resep tidak ditemukan', 404);
                }
            }

            $dataProduk = Produk::whereIn('kode', json_decode($request->kode))->get(['id', 'harga']);
            $produk_id = [];
            $hargaProduk = [];
            foreach ($dataProduk as $item) {
                $produk_id[] = $item->id;
                $hargaProduk[] = $item->harga;
            }

            $stokProduk = Produk::whereIn('kode', json_decode($request->kode))->get();
            $hasilPengurangan = [];

            foreach ($stokProduk as $produk) {
                $pengurangan = $produk->stok;
                if($stokProduk > json_decode($request->jumlah)){
                    foreach (json_decode($request->jumlah) as $jumlahProduk) {
                        $pengurangan -= $jumlahProduk;
                    }
                    $hasilPengurangan[] = $pengurangan;
                    $produk->update(['stok' => $pengurangan]);
                }else{
                    return response('Stok tidak Cukup', 400);
                }
            }

            $data = [
                'kodePenjualan' => $request->kodePenjualan,
                'produk_id' => json_encode($produk_id),
                'apoteker_id' => auth()->user()->id,
                'dokter_id' => isset($dokter_id) ? $dokter_id : 0,
                'pasien_id' => $pasien_id,
                'dsc' => $request->dsc,
                'kategoriPenjualan' => $request->kategoriPenjualan,
                'harga' => json_encode($hargaProduk),
                'jumlah' => $request->jumlah,
                'subtotal' => $request->subtotal,
            ];

            Penjualan::create($data);
            $namaPasien = User::where('id', $data['pasien_id'])->pluck('nama')->first();
            $saldoServer = Keuangan::orderBy('created_at','desc')->pluck('saldo')->first();
            $forKeuangan = [
                'keterangan' => 'Transaksi Obat Pasien '.$namaPasien,
                'jumlah' => $data['subtotal'],
                'user_id' => auth()->user()->id,
                'saldo' => $saldoServer + $data['subtotal'],
                'kategori' => 'Debit'
            ];
            Keuangan::create($forKeuangan);
            // return response()->json(['data' => [$produk_id, $hargaProduk]], 200);
            return response()->json(['message' => 'Transaksi berhasil diproses'], 200);
        }else{
            return response('Server Disconnected', 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function apotekerGetInvoice(Request $request, $kode){
        if($request->ajax()){
            $data = Penjualan::where('kodePenjualan', $kode)->get();
            $dataInvoice = [];
            foreach ($data as $item) {
                $namaPasien = User::where('id', $item->pasien_id)->pluck('nama');
                $dataInvoice[] = [
                    'kode' => $item->kodePenjualan,
                    'namaProduk' => Produk::whereIn('id', json_decode($item->produk_id))->pluck('namaProduk')->toArray(),
                    'namaApoteker' => User::where('id', $item->apoteker_id)->pluck('nama'),
                    'namaDokter' => User::where('id', $item->dokter_id)->pluck('nama'),
                    'namaPasien' => isset($namaPasien) ? $namaPasien : 'Data Pasien sudah Dihapus',
                    'harga' => json_decode($item->harga),
                    'kategori' => $item->kategoriPenjualan,
                    'jumlah' => json_decode($item->jumlah),
                    'deskripsi' => $item->deskripsi,
                    'subtotal' => $item->subtotal,
                    'created_at' => $item->created_at->format('d/m/Y H:i'),
                ];
            }

            return response()->json(['data' => $dataInvoice], 200);
        }else{
            abort(400);
        }
    }



    public function show(Request $request)
    {
        if($request->ajax()){
            $currentMonth = Carbon::now()->format('m');
            $currentYear = Carbon::now()->format('Y');
            if($request->month !== null){
                $monthNum = Carbon::parse($request->month)->format('m');
                $data = Penjualan::whereMonth('created_at', $monthNum)->with('pasien')->get();
                if($request->year !== null){
                    $data = Penjualan::whereMonth('created_at', $monthNum)
                            ->whereYear('created_at', $request->year)
                            ->with('pasien')
                            ->get();
                }
            }elseif($request->year !== null){
                $data = Penjualan::whereYear('created_at', $request->year)
                        ->with('pasien')
                        ->get();
            }else{
                $data = Penjualan::whereMonth('created_at', $currentMonth)
                    ->whereYear('created_at', $currentYear)
                    ->with('pasien')->get();
            }
            return response()->json(['data' => $data]);
        }else{
            return response('Server Disconnected', 400);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Penjualan $penjualan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePenjualanRequest $request, Penjualan $penjualan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Penjualan $penjualan)
    {
        //
    }
}
