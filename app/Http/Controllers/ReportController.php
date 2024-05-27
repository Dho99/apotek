<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{

    public function laporanKunjungan(Request $request)
    {
        if($request->ajax()){
            $monthRange = range(11, 0);
            $monthName = [];
            $yearKunjungan = [];
            $oldPatient = [];
            $newPatient = [];
            foreach($monthRange as $month){
                $getData = Kunjungan::whereYear('created_at','>=',now()->subYear())->whereMonth('created_at',Carbon::now()->subMonth($month))->with('patient')->get();
                $getMonthName = now()->submonth($month)->monthName.' '. now()->submonth($month)->format('Y');
                $sumNewPatient = 0;
                $sumOldPatient = 0;
                foreach($getData as $d){
                    if(Carbon::parse($d->patient->created_at)->gte(now()->subdays(7))){
                        $sumNewPatient+=1;
                    }else{
                        $sumOldPatient+=1;
                    }
                }
                $countedData = $getData->count();
                array_push($newPatient, $sumNewPatient);
                array_push($oldPatient, $sumOldPatient);
                array_push($yearKunjungan, $countedData);
                array_push($monthName, $getMonthName);
            }

            return response()->json([
                'monthName' => $monthName,
                'yearKunjungan' => $yearKunjungan,
                'oldPatient' => $oldPatient,
                'newPatient' => $newPatient
            ]);
        }
        // dd($yearKunjungan, $monthName, $oldPatient, $newPatient);
        return view('administrator.laporan.kunjungan', [
            'title' => 'Laporan Kunjungan',
            'kunjungan' => Kunjungan::whereYear('created_at','>=',now()->subYear())->with('dokter','patient')->get(),
        ]);

    }

}
