<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Kategori;

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
        ]);
    }

    public function filterProduk(Request $request, $filter)
    {
        $all = Produk::all();
        $relationedData = [];
        foreach ($all as $item) {
            $golonganObat = Kategori::whereIn('id', json_decode($item['golongan_id']))
                ->pluck('golongan')
                ->toArray();
            $supplier = Supplier::where('id', $item->supplier_id)
                ->get('nama')
                ->toArray();

            $relationedData[] = [
                'kode' => $item->kode,
                'namaProduk' => $item->namaProduk,
                'golongan' => $golonganObat,
                'satuan' => $item->satuan,
                'stok' => $item->stok,
                'expDate' => $item->expDate,
                'diprosesPada' => $item->created_at->format('d-m-Y'),
                'supplier' => $supplier[0],
            ];
        }

        if ($request->ajax()) {
            if ($filter === 'Semua') {
                $data = $relationedData;
            } else {
                if ($filter !== "") {
                    $filterKategori = Kategori::where('golongan', 'like', '%' . $filter . '%')->pluck('id');

                    $filterBarang = Produk::where(function ($query) use ($filterKategori) {
                        foreach ($filterKategori as $kategoriId) {
                            $query->orWhereJsonContains('golongan_id', ["$kategoriId"]);
                        }
                    })->get();


                    $onlyFiltered = [];
                    foreach ($filterBarang as $item) {
                        $golonganObatFiltered = Kategori::whereIn('id', json_decode($item['golongan_id']))
                        ->pluck('golongan')
                        ->toArray();


                        $onlyFiltered[] = [
                            'kode' => $item->kode,
                            'namaProduk' => $item->namaProduk,
                            'golongan' => $golonganObatFiltered,
                            'satuan' => $item->satuan,
                            'stok' => $item->stok,
                            'expDate' => $item->expDate,
                            'diprosesPada' => $item->created_at->format('d-m-Y'),
                            'supplier' => $supplier[0],
                        ];
                    }

                    return response()->json(['data' => $onlyFiltered]);
                }
            }

            return response()->json(['data' => $data]);
        } else {
            abort(400);
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
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        // $supplier = Supplier::where('kode', $request->supplier)->get('id');
        $data = [
            'kode' => $request->kode,
            'namaProduk' => $request->namaProduk,
            'satuan' => $request->satuan,
            'stok' => $request->stok,
            'supplier_id' => $request->supplier,
            'golongan_id' => $request->golongan,
            'expDate' => $request->expDate,
            'image' => $request->image,
        ];

        $validator = Validator::make($data, [
            'kode' => 'required',
            'namaProduk' => 'required',
            'satuan' => 'required',
            'stok' => 'required',
            'supplier_id' => 'required',
            'golongan_id' => 'required|json',
            'expDate' => 'required',
            'image' => 'image|file|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }



        $validatedData = $data;
        $validatedData['image'] = $data['image']->store('post-images');
        $submit = Produk::create($validatedData);

        return response()->json(['message' => 'Data Berhasil Disimpan'], 200);
    }




    public function apotekerShow(Request $request, $kode)
    {
        $datas = Produk::where('kode', $kode)->get();
        return view('apoteker.obat.edit-data', [
            'title' => 'Detail Data Obat',
            'datas' => $datas,
            'golongan' => Kategori::get(),
            'pemasok' => Supplier::all()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Produk $produk)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function apotekerUpdate(Request $request, $kode)
    {
        $data = [
            'kode' => $request->kode,
            'namaProduk' => $request->namaProduk,
            'satuan' => $request->satuan,
            'stok' => $request->stok,
            'supplier_id' => $request->supplier,
            'golongan_id' => $request->golongan,
            'expDate' => $request->expDate,
        ];

        $validator = Validator::make($data, [
            'kode' => 'required',
            'namaProduk' => 'required',
            'satuan' => 'required',
            'stok' => 'required',
            'supplier_id' => 'required',
            'golongan_id' => 'required|json',
            'expDate' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validatedData = $data;
        $submit = Produk::where('kode', $kode)->update($validatedData);

        return response()->json(['message' => 'Data Berhasil Disimpan'], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produk $produk, $kode)
    {
        $data = Produk::where('kode', $kode)->delete();
    }

    public function apotekerStockIn(){
        return view('apoteker.obat.tambah-stok', [
            'title' => 'Stock-in Obat',
            'data' => Produk::all()
        ]);
    }

    public function apotekerAddStock($kode){
        $data = Produk::where('kode', $kode)->first();
        return response()->json(['data' => $data->toArray()]);
    }

    public function apotekerUpdateStock(Request $request, $kode) {

        $data = Produk::where('kode', $kode)->first();
        $receivedStok = $request->stok;
        $stok = json_decode($receivedStok) + $data->stok;
        Produk::where('kode', $kode)->update([
            'stok' => $stok,
            'expDate' => $request->expDate,
        ]);

        return response()->json(['message' => 'Stok berhasil Ditambahkan'], 200);
        // return response()->json(['data' => $stok]);
    }



}
