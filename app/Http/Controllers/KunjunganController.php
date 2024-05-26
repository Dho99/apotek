<?php

namespace App\Http\Controllers;

use Alert;
use App\Models\User;
use App\Models\Pasien;
use App\Models\Kunjungan;
use Illuminate\Http\Request;
use Exception as ex;

class KunjunganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('administrator.kunjungan.daftar', [
            'title' => 'Daftar Kunjungan',
            'kunjungan' => Kunjungan::with('patient')->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('administrator.kunjungan.create', [
            'title' => 'Buat Data Kunjungan',
            'no_rm' => Pasien::getAll()
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $getPasien = Pasien::where('kode', $request->kode)->first();
        $request['no_rekam_medis'] = $getPasien->no_rekam_medis;
        $request['pasienId'] = $getPasien->id;
        if(isset($request->no_rekam_medis)){
            if($getPasien->status !== 'Aktif'){
                Alert::info('Informasi','Pasien Tidak Aktif, lakukan registrasi Ulang');
                return redirect()->back();
            }else{
                $validate = $request->validate([
                    // 'no_rekam_medis' => 'required',
                    'keluhan' => 'required|min:10',
                    'pasienId' => 'required'
                ]);
                try{
                    Kunjungan::create($validate);
                    Alert::success('Success','Data Kunjungan berhasil Disimpan');
                    return redirect()->back();
                }catch(ex $e){
                    Alert::error('Terjadi Kesalahan',$e->getMessage());
                    return redirect()->back();
                }
            }
        }else{
            return response()->json(['data' => '404'], 201);

        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Kunjungan $kunjungan, Request $request)
    {
        $kunjungan->load('patient','dokter');
        if($request->ajax()){
            return response()->json(['result' => $kunjungan], 200);
        }else{

        }

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kunjungan $kunjungan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kunjungan $kunjungan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kunjungan $kunjungan)
    {
        $kunjungan->delete();
        Alert::success('Success','Data Kunjungan berhasil Dihapus');
        return redirect()->back();
    }
}
