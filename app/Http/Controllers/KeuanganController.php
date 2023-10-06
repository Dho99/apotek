<?php

namespace App\Http\Controllers;

use App\Models\Keuangan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class KeuanganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perBulan = Keuangan::get()->groupBy(function($month){
            return Carbon::parse($month->created_at)->format('F');
        });
        $perTahun = Keuangan::get()->groupBy(function($month){
            return Carbon::parse($month->created_at)->format('Y');
        });
        // dd($perBulan);
        return view('apoteker.laporan.keuangan', [
            'title' => 'Laporan Keuangan',
            'kategori' => Keuangan::groupBy('kategori')->pluck('kategori'),
            'perBulan' => $perBulan,
            'perTahun' => $perTahun
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */

    public function getByKategori(Request $request)
    {
        if ($request->ajax()) {
            $totalSaldo = Keuangan::orderBy('created_at','desc')->pluck('saldo')->first();
            if ($request->kategori !== null)
            {
                $data = Keuangan::with('user')
                    ->where('kategori', $request->kategori)
                    ->get();
                return response()->json(['data' => $data, 'saldo' => $totalSaldo]);
            }
            elseif ($request->kode !== null)
            {
                $data = Keuangan::with('user')
                    ->where('id', $request->kode)
                    ->get();
                return response()->json(['data' => $data, 'modal' => 'true', 'saldo' => $totalSaldo]);
            }
             elseif ($request->month !== null)
            {
                $monthNumber = Carbon::parse($request->month)->format('m');
                $data = Keuangan::with('user')
                    ->whereMonth('created_at', $monthNumber)
                    ->get();
                if($request->year !== null){
                    $data = Keuangan::with('user')
                    ->whereMonth('created_at', $monthNumber)
                    ->whereYear('created_at', $request->year)
                    ->get();
                }

                return response()->json(['data' => $data, 'saldo' => $totalSaldo]);
            }
             elseif ($request->year !== null)
            {
                $data = Keuangan::with('user')
                    ->whereYear('created_at', $request->year)
                    ->get();

                return response()->json(['data' => $data, 'saldo' => $totalSaldo]);
            }
            else
            {
                $data = Keuangan::with('user')->get();
                return response()->json(['data' => $data, 'saldo' => $totalSaldo]);
            }
        } else {
            abort(400);
        }
    }

    public function create(Request $request)
    {
        if ($request->ajax()) {
            $totalKas = Keuangan::orderBy('created_at','desc')->pluck('saldo')->first();
            $data = [
                'keterangan' => $request->keterangan,
                'jumlah' => $request->jumlah,
                'user_id' => auth()->user()->id,
                'kategori' => $request->kategori,
                'saldo' => []
             ];
            if ($request->kategori === 'Kredit') {
                if ($request->jumlah < $totalKas) {
                    $data['saldo'] = $totalKas - $request->jumlah;
                    Keuangan::create($data);
                    return response()->json(['message' => 'Data berhasil disimpan']);
                } else {
                    return response()->json(['fail' => 'Saldo tidak mencukupi']);
                }
            } elseif ($request->kategori === 'Debit') {
                $data['saldo'] = $totalKas + $request->jumlah;
                Keuangan::create($data);
                return response()->json(['message' => 'Data berhasil disimpan']);
            } else {
                abort(400);
            }
        } else {
            abort(400);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreKeuanganRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Keuangan $keuangan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Keuangan $keuangan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateKeuanganRequest $request, Keuangan $keuangan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Keuangan $keuangan)
    {
        //
    }
}
