<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Pasien;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Alert;

class PasienController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('administrator.pasien.list', [
            'title' => 'Daftar Pasien',
            'pasiens' => Pasien::getAll(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('other.account-create', [
            'title' => 'Tambah Data Pasien',
            'roles' => Role::all(),
            'create_type' => 'pasien'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $this->validateInput($request);
        $dataRekamMedis = $request->validate([
            'no_rekam_medis' => 'required|min:6|unique:users,no_rekam_medis'
        ]);

        $validate['no_rekam_medis'] = $dataRekamMedis['no_rekam_medis'];

        // dd($validate);
        try {
            Pasien::create($validate);
            Alert::success('Success', 'Data Pasien berhasil Dibuat');
            return redirect()->route('pasien.index');
        } catch (\Exception $e) {
            Alert::error('error', $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Pasien $pasien, Request $request)
    {
        if($request->ajax()){
            return response()->json(['result' => $pasien], 200);
        }else{
            $pasien->load('role');
            return view('other.account-info', [
                'title' => 'Detail data Pasien',
                'data' => $pasien,
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pasien $pasien)
    {
        $pasien->load('role');
        return view('other.account-edit', [
            'title' => 'Edit Data Pasien',
            'data' => $pasien,
            'roles' => Role::all(),
        ]);
    }

    private function validateInput($params)
    {
        $params['date'] = Carbon::now()->format('Y-m-d');
        $validate = $params->validate([
            'no_rekam_medis' => 'required|min:6',
            'nama' => 'required|min:8',
            'username' => 'required|min:8',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'alamat' => 'required|min:10|max:100',
            'tanggal_lahir' => 'required|date|before:date',
        ]);

        if ($params->file('profile')) {
            $validate['profile'] = $params->file('profile')->store('profile-images');
        }

        $validate['password'] = bcrypt($params->password);
        $validate['roleId'] = 3;

        return $validate;

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pasien $pasien)
    {
        $request['no_rekam_medis'] = $pasien->no_rekam_medis;
        $validate = $this->validateInput($request);
        try {
            $pasien->update($validate);
            Alert::success('Success', 'Data Pasien berhasil Diperbarui');
            return redirect()->route('pasien.index');
        } catch (\Exception $e) {
            Alert::error('error', $e->getMessage());
            return redirect()->back();
        }
    }

    private function validateKode()
    {
        $kode = [
            'kode' => 'PAS-' . mt_rand(0000, 9999),
        ];

        $validateKode = Validator::make($kode, [
            'kode' => 'required|unique:users,kode',
        ]);

        if ($validateKode->fails()) {
            return $this->validateKode();
        } else {
            return $kode['kode'];
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pasien $pasien)
    {
        $pasien->delete();
        Alert::success('Success','Data Pasien berhasil Dihapus');
        return redirect()->back();
    }
}
