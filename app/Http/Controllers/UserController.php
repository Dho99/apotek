<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function apotekerIndex()
    {
        return view('apoteker.dokter.list', [
            'title' => 'Daftar Dokter',
            'dokter' => User::where('level', 0)->get(),
            'kategoriDokter' => User::where('level', '0')
                ->groupBy('kategoriDokter')
                ->get(),
        ]);
    }

    public function apotekerGetAllDokterAjax(Request $request)
    {
        if ($request->ajax()) {
            $data = User::where('level', 0)->get();
            return response()->json(['data' => $data]);
        } else {
            abort(400);
        }
    }

    public function apotekerFilterDokterByCategory(Request $request, $kategori)
    {
        if ($request->ajax()) {
            $data = User::where('kategoriDokter', '=', $kategori)->get();
            return response()->json(['data' => $data]);
        } else {
            abort(400);
        }
    }

    public function apotekerShowDokter(Request $request, $kode)
    {
        $dokterShow = User::where('kode', $kode)->get();
        if ($request->ajax()) {
            return response()->json(['data' => $dokterShow]);
        } else {
            abort(500);
        }
    }

    public function apotekerUpdateUser(Request $request)
    {
        if ($request->ajax()) {
            $kode = $request->kode;
            if($request->kategori === 'undefined'){
                $requestKategori = '';
            }else{
                $requestKategori = $request->kategori;
            }

            $data = [
                'kode' => $request->kode,
                'nama' => $request->nama,
                'username' => $request->username,
                'kategoriDokter' => $requestKategori,
                'alamat' => $request->alamat,
                'email' => $request->email,
                'level' => $request->level,
                'tanggal_lahir' => Carbon::parse($request->tanggal_lahir),
            ];

            $kodeFromServer = User::where('kode', $kode)->first();
            $hasil = [];
            if (isset($kodeFromServer)) {
                User::where('kode', $kode)->update($data);
                return response()->json(['message' => 'Data User '.$data['nama'].' Berhasil di perbarui']);
            } else {
                User::create($data);
                return response()->json(['message' => 'Data User '.$data['nama'].' Berhasil di Simpan']);
            }

            // return response()->json(['data' => $hasil]);
        } else {
            abort(400);
        }
    }

    public function apotekerDeleteUser(Request $request, $kode)
    {
        if ($request->ajax()) {
            User::where('kode', $kode)->delete();
            return response()->json(['message' => 'Data User berhasil dihapus']);
        } else {
            abort(400);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function apotekerListPasien()
    {
        return view('apoteker.user.list', [
            'title' => 'Daftar User',
            'datas' => User::all(),
            'kategoriUser' => User::groupBy('level')
                ->orderBy('level', 'asc')
                ->get(),
        ]);
    }

    public function apotekerGetUserByLevel(Request $request, $level)
    {
        if ($request->ajax()) {
            if ($level !== 'all') {
                $data = User::where('level', $level)->get();
            } else {
                $data = User::all();
            }
            return response()->json(['data' => $data]);
        } else {
            abort(400);
        }
    }

    public function apotekerGetUser(Request $request, $kode)
    {
        if ($request->ajax()) {
            $data = User::where('kode', $kode)->get();
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
    public function store(StoreUserRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function apotekerShow($kode)
    {
        return view('other.account-info', [
            'title' => 'Account Info',
            'data' => User::where('kode', $kode)->get(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function apotekerEdit($kode)
    {
        return view('other.account-edit', [
            'title' => 'Account Edit',
            'data' => User::where('kode', $kode)->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function apotekerUpdate(Request $request, $kode)
    {
        $validateData = $request->validate([
            'username' => 'required|max:100',
            'nama' => 'required|max:50',
            'password' => 'required',
            'email' => 'required|email|max:50',
            'profile' => 'image|file|max:2048',
        ]);

        $validateData['password'] = bcrypt($validateData['password']);

        if ($request->file('profile')) {
            $validateData['profile'] = $request->file('profile')->store('profile-images');
        }

        User::where('kode', $kode)->update($validateData);

        return redirect()
            ->intended('/account/manage/' . auth()->user()->kode)
            ->with('success', 'Data Akun anda Berhasil Diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */

    public function kasirPasienList(){
        return view('kasir.pasien.list', [
            'title' => 'Daftar Pasien',
            // 'data' => User::where('level', '3')->get(),
        ]);
    }

    public function kasirGetPasien(Request $request){
        if($request->ajax()){
            $data = User::where('level', 3)->get();
            return response()->json(['data' => $data]);
        }else{
            abort(400);
        }
    }

    public function kasirGetPasienByKode(Request $request, $kode){
        if($request->ajax()){
            $data = User::where('kode', $kode)->get();
            return response()->json(['data' => $data]);
        }else{
            abort(400);
        }
    }

    public function kasirUpdatePasien(Request $request){
        if($request->ajax()){
            $kodeFromServer = User::where('nama', $request->nama)->pluck('kode')->first();
            $data = [
                'kode' => $request->kode,
                'nama' => $request->nama,
                'username' => $request->username,
                'email' => $request->email,
                'alamat' => $request->alamat,
                'tanggal_lahir' => $request->tanggal_lahir,
                'level' => '3',
                'status' => 'Aktif'
            ];
            if(isset($kodeFromServer)){
                $data['kode'] = $kodeFromServer;
                User::where('kode', $data['kode'])->update($data);
                return response()->json(['message' => 'Data berhasil diperbarui']);
            }else{
                User::create($data);
                return response()->json(['message'=>'Data berhasil disimpan']);
            }

        }else{
            abort(400);
        }
    }

    public function kasirDeletePasien(Request $request, $kode){
        if($request->ajax()){
            User::where('kode', $kode)->delete();
            return response()->json(['message' => 'Data berhasil dihapus']);
        }else{
            abort(400);
        }
    }

}
