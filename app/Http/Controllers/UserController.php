<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

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
            'kategoriDokter' => User::where('level', '0')->groupBy('kategoriDokter')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
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
            ->intended('/apoteker/account/manage/' . auth()->user()->kode)
            ->with('success', 'Data Akun anda Berhasil Diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
