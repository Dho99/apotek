<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
// use App\Events\UserNotification;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('other.supplier.list', [
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

    public function apotekerUpdateSupplier(Request $request)
    {
        if ($request->ajax()) {
            $kode = $request->kode;
            $data = [
                'kode' => $request->kode,
                'nama' => $request->namaSupplier,
                'alamat' => $request->alamat,
                'perwakilan'=> $request->perwakilan,
                'noTel' => $request->noTelp
            ];
            $kodeFromServer = Supplier::where('kode', $kode)->first();
            if(isset($kodeFromServer)){
                Supplier::where('kode', $kode)->update($data);
                $action = 'Memperbarui';
                return response()->json(['message' => 'Data supplier '.$data['nama'].' berhasil di Perbarui']);
            }else{
                Supplier::create($data);
                $action = 'Membuat';
                return response()->json(['message' => 'Data Supplier '.$data['nama'].' berhasil di Simpan']);
            }

            // event(new UserNotification('Apoteker telah berhasil '.$action.' data Supplier '.$data['nama'], auth()->user()));

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

    public function apotekerDeleteSupplier(Request $request, $kode)
    {
        if ($request->ajax()) {
            // event(new UserNotification('Apoteker berhasil menghapus data Supplier '.Supplier::where('kode', $kode)->pluck('nama')->first(), auth()->user()));
            Supplier::where('kode', $kode)->delete();
            //event for all users
            return response()->json(['message' => 'Data supplier ' . $kode . ' berhasil dihapus']);
        } else {
            abort(400);
        }
    }
}
