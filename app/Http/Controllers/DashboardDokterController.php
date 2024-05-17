<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardDokterController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax()){

            $dateOfDays = [];
            $weekDays = range(6,0);
            foreach($weekDays as $d){
                $consult = Kunjungan::whereDay('created_at',now()->subDays($d)->format('d'))->count();
                array_push($dateOfDays, [now()->subDays($d)->locale('id_ID')->dayName, $consult]);
            }

            return response()->json(['chartData' => $dateOfDays], 200);

        }else{
            $userId = auth()->user()->id;
            // Jumlahkan semua data kunjungan yang berkonsultasi dengan dokter
            $patients = Kunjungan::with('patient')->where('dokterId', $userId)->get();

            $patientsTime = [
                'old' => 0,
                'new' => 0
            ];

            foreach($patients as $p){
                if($p->patient->created_at->gte(now()->subdays(30))){
                    $patientsTime['new']++;
                }else{
                    $patientsTime['old']++;
                }
            }

            return view('dokter.dashboard', [
                'title' => 'Dashboard',
                'consultOrder' => count($patients),
                'oldPatientCounts' => $patientsTime['old'],
                'newPatientCounts' => $patientsTime['new'],
            ]);

        }
    }
}
