<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardDokterController extends Controller
{
    public function index()
    {
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

        // dapatkan data kunjungan berkonsultasi dengan dokter dalam 7 hari
        // $getPatientsByDay = Kunjungan::where('created_at','>=',now()->subdays(7)->startOfDay())->groupBy('created_at')->with('patient')->get();

        $dateOfDays = [];
        $weekDays = range(6,0);
        foreach($weekDays as $d){
            $consult = Kunjungan::whereDay('created_at',now()->subDays($d)->format('d'))->count();
            array_push($dateOfDays, [now()->subDays($d)->locale('id_ID')->dayName, $consult]);
        }



        // dd($dateOfDays);

        return view('dokter.dashboard', [
            'title' => 'Dashboard',
            'consultOrder' => count($patients),
            'oldPatientCounts' => $patientsTime['old'],
            'newPatientCounts' => $patientsTime['new'],
            'chartData' => json_encode($dateOfDays)
        ]);
    }
}
