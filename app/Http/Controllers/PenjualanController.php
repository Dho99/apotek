<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('apoteker.laporan.penjualan', [
            'title' => 'Laporan Penjualan',
            'yearOption' => Penjualan::orderBy('created_at','desc')->get()->groupBy(function($item){
                return $item->created_at->format('Y');
            }),
            'datas' => Penjualan::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function getDataPenjualan(Request $request, $year)
    {
        if ($request->ajax()) {
            $data = Penjualan::whereYear('created_at' , $year )->get(['jumlah', 'subtotal','created_at'])->groupBy(function ($date) {
                return Carbon::parse($date->created_at)->format('F');
            });
            return response()->json(['data' => $data]);
        } else {
            abort(400);
        }
    }

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (request()->ajax()) {
            if (isset($request->kodeDokter) && !isset($request->kodePasien)) {
                $dokter_id = $request->kodeDokter;
                $pasien_id = $request->kodePasien;
            } else {
                $dokter_id = 0;
                $pasien_id = 0;
            }
            $dataProduk = Produk::whereIn('kode', $request->kode)->get(['id', 'harga']);
            $produk_id = [];
            $hargaProduk = [];
            foreach ($dataProduk as $item) {
                $produk_id[] = $item->id;
                $hargaProduk[] = $item->harga;
            }
            // $stokProduk = Produk::whereIn('kode', $request->kode)->get();
            // $hasilPengurangan = [];

            // foreach ($stokProduk as $produk) {
            //     $pengurangan = $produk->stok;
            // }
            // foreach ($request->jumlah as $jumlahProduk) {
            //     $hasilPengurangan[] = $pengurangan - $jumlahProduk;
            // }

            // foreach($hasilPengurangan as $item){
            //     Produk::whereIn('kode', $request->kode)->update([
            //         'stok' => $item
            //     ]);
            // }

            $stokProduk = Produk::whereIn('kode', $request->kode)->get();
            $hasilPengurangan = [];

            foreach ($stokProduk as $produk) {
                // Ubah pengurangan ke dalam loop ini untuk setiap produk
                $pengurangan = $produk->stok;

                foreach ($request->jumlah as $jumlahProduk) {
                    // Kurangi stok setiap produk dengan jumlah yang sesuai
                    $pengurangan -= $jumlahProduk;
                }

                // Tambahkan hasil pengurangan ke dalam array
                $hasilPengurangan[] = $pengurangan;

                // Update stok produk saat ini
                $produk->update(['stok' => $pengurangan]);
            }

            $data = [
                'kodePenjualan' => $request->kodePenjualan,
                'produk_id' => json_encode($produk_id),
                'apoteker_id' => auth()->user()->id,
                'dokter_id' => $dokter_id,
                'pasien_id' => $pasien_id,
                'dsc' => $request->dscField,
                'kategoriPenjualan' => $request->kategoriPenjualan,
                'harga' => json_encode($hargaProduk),
                'jumlah' => json_encode($request->jumlah),
                'subtotal' => $request->subtotal,
            ];

            Penjualan::create($data);
            return response()->json(['message' => 'Produk berhasil diproses', 'data' => $hasilPengurangan]);
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
                $dataInvoice[] = [
                    'kode' => $item->kodePenjualan,
                    'namaProduk' => Produk::whereIn('id', json_decode($item->produk_id))->pluck('namaProduk')->toArray(),
                    'namaApoteker' => User::where('id', $item->apoteker_id)->pluck('nama'),
                    'namaDokter' => User::where('id', $item->dokter_id)->pluck('nama'),
                    'namaPasien' => User::where('id', $item->pasien_id)->pluck('nama'),
                    'harga' => json_decode($item->harga),
                    'kategori' => $item->kategoriPenjualan,
                    'jumlah' => json_decode($item->jumlah),
                    'deskripsi' => $item->dsc,
                    'subtotal' => $item->subtotal,
                    'created_at' => $item->created_at->format('d/m/Y H:i'),
                ];
            }

            return response()->json(['data' => $dataInvoice]);
        }else{
            abort(400);
        }
    }



    public function show(Penjualan $penjualan)
    {
        //
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
