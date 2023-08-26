<?php

namespace App\Http\Controllers;

use App\Models\Resep;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Produk;

class ResepController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // Blok Umum
    public function getResep(Request $request)
    {
        if ($request->ajax()) {
            $data = Resep::with('pasien', 'obat')
                ->where('isProceed', 0)
                ->get();
            return response()->json(['data' => $data]);
        } else {
            abort(400);
        }
    }

    public function apotekerProsesResep(Request $request, $kode){

        Resep::where('kode', $kode)->update([
            'isProceedByApoteker' => 1
        ]);

        return back()->with('success', 'Data Proses Berhasil di Proses');

    }

    public function apotekerIndex(){
        return view('apoteker.kasir.list', [
            'title' => 'Kasir',
        ]);
    }

    public function apotekerIndexFilter($satuan){

    }


    //  Blok Apoteker
    public function dokterIndex()
    {
        return view('apoteker.resep.list', [
            'title' => 'Daftar Resep',
            'data' => Resep::with('obat', 'dokter')->paginate(7),
            'pasien' => Resep::with('pasien', 'obat')->get(),
        ]);
    }



    public function dokterCreate(Request $request)
    {
        $data = Resep::with('pasien', 'obat')
            ->where('isProceed', 0)
            ->get();
        return view('dokter.resep.create', [
            'title' => 'Buat Resep',
            'pasien' => $data,
            'obat' => Produk::all(),
            'satuan' => Produk::groupBy('satuan')->get(),
        ]);
    }

    // Blok Dokter
    public function dokterStore(Request $request)
    {
        $input = $request->all();
        $data = [
            'obat_id' => $input['obatId'],
            'jumlah' => $input['jumlah'],
            'satuan' => $input['satuanObat'], // Pastikan ini valid sesuai tipe data di basis data
            'catatan' => $input['catatan'],
            'isProceed' => 1,
        ];

        $updatedData = Resep::where('kode', $input['kode'])->update($data);

        return back()->with('success', 'Resep Berhasil Dikirim');
    }

    /**
     * Display the specified resource.
     */
    public function show(Resep $resep)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Resep $resep)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateResepRequest $request, Resep $resep)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Resep $resep)
    {
        //
    }
}
