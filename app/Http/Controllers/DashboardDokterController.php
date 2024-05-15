<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use Illuminate\Http\Request;

class DashboardDokterController extends Controller
{
    public function index()
    {
        $patients = Kunjungan::with('patient')->where('dokterId', auth()->user()->id)->get();
        $patientCounts = [
            'newPatient' => [],
            'oldPatient' => [],
        ];

        foreach($patients as $p){
            if($p->patient->created_at <= now()->subdays(7)){
                $pushOldPatient = array_push($patientCounts['oldPatient'], $p);
            }else{
                $pushNewPatient = array_push($patientCounts['newPatient'], $p);
            }
        }

        // dd($patientCounts);
        return view('dokter.dashboard', [
            'title' => 'Dashboard',
            'consultOrder' => $patients,
            'patientCounts' => $patientCounts
        ]);
    }
}
