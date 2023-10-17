<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Keuangan;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
// use App\Events\UserNotification;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function apotekerIndex()
    {
        $data = Kategori::all();
        return view('apoteker.obat.list', [
            'title' => 'Daftar Obat',
            'kategori' => $data,
            'satuan' => Produk::groupBy('satuan')->get()
        ]);
    }

    public function filterProduk(Request $request, $filter)
    {
        $all = Produk::with('supplier')->get();
        $relationedData = [];
        foreach ($all as $item) {
            $golonganObat = Kategori::whereIn('id', json_decode($item['golongan_id']))
                ->pluck('golongan')
                ->toArray();

            $relationedData[] = [
                'kode' => $item->kode,
                'namaProduk' => $item->namaProduk,
                'golongan' => $golonganObat,
                'satuan' => $item->satuan,
                'stok' => $item->stok,
                'expDate' => $item->expDate,
                'diprosesPada' => $item->created_at->format('d-m-Y'),
                'supplier' => isset($item->supplier->nama) ? $item->supplier->nama : 'Supplier telah tidak bekerja sama',
            ];
        }

        if ($request->ajax()) {
            if ($filter === 'Semua' || $filter === '') {
                $data = $relationedData;
                return response()->json(['data' => $data]);
            } else {
                if ($filter !== '') {
                    $filterKategori = Kategori::where('golongan', 'like', '%' . $filter . '%')->pluck('id')->toArray();

                    $filterBarang = Produk::where(function ($query) use ($filterKategori) {
                        foreach ($filterKategori as $kategoriId) {
                            $query->orWhereJsonContains('golongan_id', [$kategoriId]);
                        }
                    })->get();



                    $onlyFiltered = [];
                    foreach ($filterBarang as $item) {
                        $golonganObat = Kategori::whereIn('id', json_decode($item->golongan_id))
                        ->pluck('golongan')
                        ->toArray();

                    $onlyFiltered[] = [
                            'kode' => $item->kode,
                            'namaProduk' => $item->namaProduk,
                            'golongan' => $golonganObat,
                            'satuan' => $item->satuan,
                            'stok' => $item->stok,
                            'expDate' => $item->expDate,
                            'diprosesPada' => $item->created_at->format('d-m-Y'),
                            'supplier' => isset($item->supplier->nama) ? $item->supplier->nama : 'Supplier telah tidak bekerja sama',
                        ];
                    }

                    return response()->json(['data' => $onlyFiltered]);
                }
            }
        } else {
            return response('Server disconnected',400);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function apotekerCreate()
    {
        return view('apoteker.obat.tambah-data', [
            'title' => 'Tambah Data Obat',
            'golongan' => Kategori::all(),
            'pemasok' => Supplier::all(),
            'satuan' => Produk::groupBy('satuan')->pluck('satuan'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        foreach (json_decode($request->golongan) as $item){
            $cekKategori = Kategori::where('id', $item)->get()->first();
            if(isset($cekKategori)){
                $golongan_id[] = $cekKategori->id;
                // $golongan_id[] = 'Ada';
            }else{
                try {
                    $kategoriBaru = Kategori::create([
                        'golongan' => $item,
                        'keterangan' => $item
                    ]);
                    $golongan_id[] = $kategoriBaru->id;
                } catch (\Exception $e) {
                    return response(500);
                }
            }
        }

        $cekSupplier = Supplier::where('id', $request->supplier)->pluck('id')->first();
        if(isset($cekSupplier)){
            $supplier_id = $cekSupplier;
        }else{
            $newSupplier = Supplier::create([
                'kode' => 'SUP-'.mt_rand(0000,9999),
                'nama' => $request->supplier,
                'alamat' => fake()->address(),
                'noTel' => fake()->phoneNumber(),
                'perwakilan' => fake()->name()
            ]);
            $supplier_id = $newSupplier->id;
        }

        $data = [
            'kode' => $request->kode,
            'namaProduk' => $request->namaProduk,
            'satuan' => $request->satuan,
            'stok' => $request->stok,
            'harga' => $request->harga,
            'supplier_id' => $supplier_id,
            'golongan_id' => json_encode($golongan_id),
            'expDate' => $request->expDate,
            'image' => $request->image,
            'deskripsi' =>$request->deskripsi
        ];

        $validator = Validator::make($data, [
            'kode' => 'required',
            'namaProduk' => 'required',
            'satuan' => 'required',
            'stok' => 'required',
            'harga' => 'required',
            'supplier_id' => 'required',
            'golongan_id' => 'required|json',
            'expDate' => 'required',
            'image' => 'image|file|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $saldo = Keuangan::orderBy('created_at', 'desc')
            ->pluck('saldo')
            ->first();
        $total = $data['harga'] * $data['stok'];

        if ($total > $saldo) {
            return response('Saldo tidak mencukupi, masukkan dana kas terlebih dahulu', 500);

        } else {
            Keuangan::create([
                'keterangan' => 'Pembelian `Pr`oduk ' . $data['namaProduk'],
                'jumlah' => $total,
                'user_id' => auth()->user()->id,
                'saldo' => $saldo - $total,
                'kategori' => 'Kredit',
            ]);

            $validatedData = $data;
            $validatedData['image'] = $data['image']->store('post-images');
            $submit = Produk::create($validatedData);

            // event for All Users
            // event(new UserNotification('Apoteker '.auth()->user()->nama.' berhasil Menambahkan data Obat '.$request->namaProduk, auth()->user()));

            return response()->json(['message' => 'Data Berhasil Disimpan']);
        }
        return response('Gagal menyimpan Data', 400);
    }

    public function apotekerShow(Request $request, $kode)
    {
        $datas = Produk::where('kode', $kode)->get();
        return view('apoteker.obat.edit-data', [
            'title' => 'Detail Data Obat',
            'datas' => $datas,
            'golongan' => Kategori::get(),
            'pemasok' => Supplier::all(),
            'satuan' => Produk::groupBy('satuan')->pluck('satuan')
        ]);
    }


    public function showDescription(Request $request, $kode){
        if($request->ajax()){
            $getData = Produk::where('kode', $kode)->pluck('deskripsi')->first();
            if(isset($getData)){
                $data = $getData;
            }else{
                return response('Data tidak ditemukan', 404);
            }
            return response()->json(['data' => $data]);
        }else{
            return response('Server Disconnected',400);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function apotekerGetProdukByKode(Request $request, $kode)
    {
        if($request->ajax()){
            $data = Produk::where('kode', $kode)->get();
            if(isset($data)){
                return response()->json(['data' => $data], 200);
            }else{
                return response('Data tidak ditemukan', 404);
            }
        }else{
            return response('Server disconnected', 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function apotekerUpdate(Request $request, $kode)
    {
        foreach (json_decode($request->golongan) as $item){
            $cekKategori = Kategori::where('id', $item)->get()->first();
            if(isset($cekKategori)){
                $golongan_id[] = $cekKategori->id;
                // $golongan_id[] = 'Ada';
            }else{
                try {
                    $kategoriBaru = Kategori::create([
                        'golongan' => $item,
                        'keterangan' => $item
                    ]);
                    $golongan_id[] = $kategoriBaru->id;
                } catch (\Exception $e) {
                    return response(500);
                }
            }
        }

        $cekSupplier = Supplier::where('id', $request->supplier)->pluck('id')->first();
        if(isset($cekSupplier)){
            $supplier_id = $cekSupplier;
        }else{
            $newSupplier = Supplier::create([
                'kode' => 'SUP-'.mt_rand(0000,9999),
                'nama' => $request->supplier,
                'alamat' => fake()->address(),
                'noTel' => fake()->phoneNumber(),
                'perwakilan' => fake()->name(2)
            ]);
            $supplier_id = $newSupplier->id;
        }

       $data = [
            'kode' => $request->kode,
            'namaProduk' => $request->namaProduk,
            'satuan' => $request->satuan,
            'stok' => $request->stok,
            'harga' => $request->harga,
            'supplier_id' => $supplier_id,
            'golongan_id' => $golongan_id,
            'expDate' => $request->expDate,
            'deskripsi' => $request->deskripsi
        ];

        $validator = Validator::make($data, [
            'kode' => 'required',
            'namaProduk' => 'required',
            'satuan' => 'required',
            'stok' => 'required',
            'harga' => 'required',
            'supplier_id' => 'required',
            'golongan_id' => 'required',
            'expDate' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

    //     // event All Users

        $validatedData = $data;
        $submit = Produk::where('kode', $kode)->update($validatedData);
        // event(new UserNotification('Apoteker '.auth()->user()->nama.' berhasil Memperbarui data Obat '.$request->namaProduk, auth()->user()));
        return response()->json(['message' => 'Data Berhasil Disimpan'], 200);
        // return response()->json(['data' => json_decode($cekKategori)]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produk $produk, $kode)
    {
        // event(new UserNotification('Apoteker '.auth()->user()->nama.' telah Menghapus data Obat '.Produk::where('kode', $kode)->pluck('namaProduk')->first(), auth()->user()));
        $data = Produk::where('kode', $kode)->delete();
        // all users event

        return response()->json(['message' => 'Data berhasil Dihapus']);
    }

    public function apotekerStockIn()
    {
        return view('apoteker.obat.tambah-stok', [
            'title' => 'Stock-in Obat',
            'data' => Produk::all(),
        ]);
    }

    public function apotekerAddStock($kode)
    {
        $data = Produk::where('kode', $kode)->first();
        return response()->json(['data' => $data->toArray()]);
    }

    public function apotekerUpdateStock(Request $request, $kode)
    {
        if ($request->ajax()) {
            if ($request->stok <= 0) {
                return response()->json(['fail' => 'Stok yang dimasukkan tidak boleh bernilai 0']);
            } else {
                $saldo = Keuangan::orderBy('created_at', 'desc')
                    ->pluck('saldo')
                    ->first();
                $stok = Produk::where('kode', $kode)->first();
                $total = $request->stok * json_decode($stok->harga);
                if (isset($stok)) {
                    if ($total < $saldo) {
                        Produk::where('kode', $kode)->update([
                            'stok' => $stok->stok + $request->stok,
                            'expDate' => $request->expDate,
                        ]);
                        Keuangan::create([
                            'keterangan' => 'Pembelanjaan produk '.$stok->namaProduk,
                            'jumlah' => $total,
                            'user_id' => auth()->user()->id,
                            'saldo' => $saldo - $total,
                            'kategori' => 'Kredit'
                        ]);
                        // event(new UserNotification('Apoteker '.auth()->user()->nama.' berhasil Menambahkan Stok Obat '.$stok->namaProduk, auth()->user()));
                        //event for all Users

                        return response()->json(['message' => 'Stok berhasil Ditambahkan']);
                    } else {
                        return response()->json(['fail' => 'Saldo kas tidak mencukupi']);
                    }
                } else {
                    return response()->json(['fail' => 'Data Obat tidak Ada']);
                }
            }
        } else {
            return response('Server disconnected', 400);
        }
    }
}
