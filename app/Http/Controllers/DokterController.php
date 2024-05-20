<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

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
        return view('apoteker.dokter.list', [
            'title' => 'Daftar Dokter',
            'dokters' => $dokters
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
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
