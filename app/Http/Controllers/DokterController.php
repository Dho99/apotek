<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Dokter;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Alert;

class DokterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $dokters = Dokter::getAll();
        if($request->ajax()){
            return response()->json([
                'dokters' => $dokters
            ]);
        }
        return view('administrator.dokter.list', [
            'title' => 'Daftar Dokter',
            'dokters' => $dokters
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // return view('other.account-create',[
        //     'title' => 'Buat data Dokter'
        // ]);
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
    public function show(Dokter $dokter)
    {
        $dokter->load('role');
        return view('other.account-info', [
            'title' => 'Info Akun',
            'data' => $dokter
        ]);
        // return response()->json(['data' => $dokter], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dokter $dokter)
    {
        return view('other.account-edit', [
            'title' => 'Edit Akun',
            'data' => $dokter,
            'roles' => Role::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dokter $dokter)
    {
        $request->validate([
            'profile' => 'nullable|max:5024',
            'username' => 'required',
            'nama' => 'required',
            'email' => 'required',
            'roleId' => 'required',
            'start' => 'required',
            'end' => 'required'
        ]);

        if(Carbon::parse($request->start) <= Carbon::parse($request->end))
        {
            $payload = $request->all();

            $payload['password'] = bcrypt($request->password);

            if ($request->file('profile')) {
                if(isset($dokter->profile)){
                    $payload['profile'] = Storage::delete($dokter->profile);
                }
                $payload['profile'] = $request->file('profile')->store('profile-images');
            }

            $payload['jamPraktek'] = json_encode([
                'start' => $request->start,
                'end' => $request->end
            ]);

            try{
                // return response()->json(['payload' => $payload], 201);
                $update = $dokter->update($payload);
                Alert::success('Success', 'Data Dokter berhasil Dihapus');
                return redirect()->route('dokter.show',[$dokter->id]);
            }catch(\Exception $e){
                return response($e->getMessage());
            }
        }



    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dokter $dokter, Request $request)
    {
        if(isset($dokter->profile)){
            Storage::delete($dokter->profile);
        }

        $dokter->delete();

        if($request->ajax()){
            return response()->json(['message' => 'Data Dokter berhasil Dihapus'], 200);
        }else{
            Alert::success('Success', 'Data Dokter berhasil Dihapus');
            return redirect()->back();
        }
    }


    public function updatePracticeTime(Request $request)
    {
        $startTime = Carbon::parse($request->start);
        $endTime = Carbon::parse($request->end);
        if($startTime <= $endTime){
            try{
                $jamPraktek = [
                    'start' => $startTime->format('H:i'),
                    'end' => $endTime->format('H:i'),
                ];
                $dokter = Dokter::where('kode', $request->code)->update(['jamPraktek' => json_encode($jamPraktek)]);
                return response(['message' => 'Data Updated Successfully'], 200);
            }catch(\Exception $e){
                return response($e->getMessage, 500);
            }
        }else{
            return response('Cannot Update Jam Praktek, Please check it again', 500);
        }
    }
}
