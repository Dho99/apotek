<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use App\Http\Requests\Request;
use Alert;

class KunjunganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('administrator.kunjungan.daftar', [
            'title' => 'Daftar Kunjungan',
            'kunjungan' => Kunjungan::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('administrator.kunjungan.create', [
            'title' => 'Buat Data Kunjungan'
        ]);
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
    public function show(Kunjungan $kunjungan)
    {
        //
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
