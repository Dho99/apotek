<?php

namespace App\Http\Controllers;

use Alert;
use App\Models\Role;
use App\Models\Kasir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class KasirController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('administrator.kasir.list', [
            'title' => 'Daftar Kasir',
            'cashiers' => Kasir::getAll(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('other.account-create', [
            'title' => 'Tambah data Kasir',
            'roles' => Role::all(),
            'create_type' => 'kasir',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'nama' => 'required|min:10',
            'username' => 'required|unique:users,username',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'profile' => 'nullable|max:5024',
            'telp' => 'required|unique:users,telp',
        ]);

        $validate['kode'] = $this->generateKode()['kode'];
        $validate['roleId'] = 4;
        $validate['password'] = bcrypt($request->password);

        if ($request->file('profile')) {
            $validate['profile'] = $request->profile->store('profile-images');
        }

        try {
            $create = Kasir::create($validate);
            Alert::success('Success', 'Data Kasir berhasil Dibuat');
            return redirect()->route('kasir.index');
        } catch (\Exception $e) {
            Alert::error('Error', $e->getMessage());
            return redirect()->back();
        }
    }

    private function generateKode()
    {
        $kode = [
            'kode' => 'KSIR-' . mt_rand(00000, 99999),
        ];

        $validateKode = Validator::make($kode, [
            'kode' => 'unique:users,kode',
        ]);

        if ($validateKode->fails()) {
            return $this->generateKode();
        } else {
            return $kode;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Kasir $kasir)
    {
        return view('other.account-info', [
            'title' => 'Detail Akun Kasir',
            'data' => $kasir,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kasir $kasir)
    {
        return view('other.account-edit', [
            'title' => 'Edit Data Apoteker',
            'data' => $kasir,
            'roles' => Role::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kasir $kasir)
    {
        $request->validate([
            'profile' => 'nullable|max:5024',
            'username' => 'required',
            'nama' => 'required',
            'email' => 'required',
            'roleId' => 'required',
            'start' => 'required',
            'end' => 'required',
        ]);

        if (Carbon::parse($request->start) <= Carbon::parse($request->end)) {
            $payload = $request->all();

            $payload['password'] = bcrypt($request->password);

            if ($request->file('profile')) {
                if (isset($kasir->profile)) {
                    $payload['profile'] = Storage::delete($kasir->profile);
                }
                $payload['profile'] = $request->file('profile')->store('profile-images');
            }

            try {
                $update = $kasir->update($payload);
                Alert::success('Success', 'Data Dokter berhasil Dihapus');
                return redirect()->route('dokter.show', [$kasir->id]);
            } catch (\Exception $e) {
                return response($e->getMessage());
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kasir $kasir)
    {
        try {
            $kasir->delete();
            Alert::success('Sukses', 'Data Kasir berhasil dihapus');
            return back();
        } catch (\Exception $e) {
            Alert::error('Terjadi Kesalahan', $e->getMessage());
            return back();
        }
    }
}
