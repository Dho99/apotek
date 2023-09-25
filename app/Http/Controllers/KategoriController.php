<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use App\Events\UserNotification;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('other.kategori-list', [
            'title' => 'Kategori Obat',
            'kategori' => Kategori::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */

    public function updateKategories(Request $request)
    {
        if($request->ajax()){
            $data = Kategori::where('golongan',$request->kategori)->first();
            $form = [
                'golongan' => $request->kategori,
                'keterangan' => $request->keterangan
            ];
            if(isset($data)){
                Kategori::where('golongan', $request->kategori)->update($form);
                event(new UserNotification(auth()->user()->nama.' Memperbarui data kategori '.$request->kategori, auth()->user()));
                return response()->json(['message' => 'Data kategori '.$request->kategori.' berhasil diperbarui']);
                // Event for all Users


            }else{
                Kategori::create($form);
                event(new UserNotification(auth()->user()->nama.' Membuat data kategori '.$request->kategori, auth()->user()));
                return response()->json(['message' => 'Data kategori '.$request->kategori.' berhasil disimpan']);
            }
        }else{
            abort(400);
        }
    }

    public function getAllKategories(Request $request){
        if($request->ajax()){
            $data = Kategori::all();
            return response()->json(['data' => $data]);
        }else{
            abort(400);
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreKategoriRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $kategori)
    {
        if($request->ajax()){
            $data = Kategori::where('golongan', $kategori)->get();
            return response()->json(['data' => $data]);
        }else{
            abort(400);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kategori $kategori)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateKategoriRequest $request, Kategori $kategori)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteKategori(Request $request, $golongan)
    {
        if($request->ajax()){
            event(new UserNotification(auth()->user()->nama.' Menghapus data kategori '.Kategori::where('golongan', $golongan)->pluck('golongan')->first(), auth()->user()));
            Kategori::where('golongan', $golongan)->delete();
            return response()->json(['message' => 'Data kategori '.$golongan.' berhasil dihapus']);
        }else{
            abort(400);
        }
    }
}
