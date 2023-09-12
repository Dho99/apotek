<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function apotekerIndex()
    {
        return view('apoteker.pemasok.list', [
            'title' => 'Daftar Pemasok',
            'data' => Supplier::all(),
        ]);
    }

    public function apotekerFindSupplier(Request $request, $kode)
    {
        if ($request->ajax()) {
            $data = Supplier::where('kode', $kode)->get();
            return response()->json(['data' => $data]);
        } else {
            abort(400);
        }
    }

    public function apotekerUpdateSupplier(Request $request, $kode)
    {
        if ($request->ajax()) {
            Supplier::where('kode', $kode)->update([
                'kode' => $request->kode,
                'nama' => $request->namaSupplier,
                'perwakilan' => $request->perwakilan,
                'alamat' => $request->alamat,
                'noTelp' => $request->noTelp,
            ]);
            return response()->json(['message' => 'Data Supplier ' . $kode . ' berhasil diperbarui']);
        } else {
            abort(400);
        }
    }

    public function apotekerGetSupplier(Request $request)
    {
        if ($request->ajax()) {
            $data = Supplier::all();
            return response()->json(['data' => $data]);
        } else {
            abort(400);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function apotekerCreateSupplier(Request $request)
    {
        if ($request->ajax()) {
            Supplier::create([
                'kode' => $request->kode,
                'nama' => $request->namaSupplier,
                'alamat' => $request->alamat,
                'perwakilan' => $request->perwakilan,
                'noTelp' => $request->noTelp,
            ]);
            return response()->json(['message' => 'Data Supplier '.$request->nama. ' berhasil ditambahkan']);
        } else {
            abort(400);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        //
    }

    public function apotekerDeleteSupplier(Request $request, $kode){
        if($request->ajax()){
            Supplier::where('kode', $kode)->delete();
            return response()->json(['message' => 'Data supplier '.$kode. ' berhasil dihapus']);
        }else{
            abort(400);
        }
    }
}
