<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use App\Models\Kunjungan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardDokterController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax()){

            if($request->time === 'week'){
                $weekDays = range(6,0);
                $dateOfDays = [];
                foreach($weekDays as $d){
                    $consult = Kunjungan::whereDay('created_at',now()->subDays($d)->format('d'))->with('patient')->get();
                    $oldPatient = 0;
                    $newPatient = 0;
                    foreach($consult as $c){
                        if($c->patient->created_at->gte(now()->subdays(30))){
                            $newPatient++;
                        }else{
                            $oldPatient++;
                        }
                    }
                    array_push($dateOfDays, [now()->subDays($d)->locale('id_ID')->dayName, count($consult), $oldPatient, $newPatient]);
                }

                return response()->json(['chartData' => $dateOfDays], 200);
            }
            if($request->time === 'month'){
                $weekDays = range(30,0);
                $dateOfDays = [];
                foreach($weekDays as $d){
                    $consult = Kunjungan::whereDay('created_at', now()->subDays($d))->with('patient')->get();
                    $oldPatient = 0;
                    $newPatient = 0;
                    foreach($consult as $c){
                        if($c->patient->created_at->gte(now()->subdays(30))){
                            $newPatient++;
                        }else{
                            $oldPatient++;
                        }
                    }
                    // array_push($dateOfDays, $consult);
                    array_push($dateOfDays, [now()->subDays($d)->locale('id_ID')->format('d F'), count($consult), $oldPatient, $newPatient]);
                }

                return response()->json(['chartData' => $dateOfDays], 200);
            }

            if($request->time === 'year'){
                $weekDays = range(11,0);
                $dateOfDays = [];
                foreach($weekDays as $d){
                    $consult = Kunjungan::whereMonth('created_at', now()->subMonth($d))->with('patient')->get();
                    $oldPatient = 0;
                    $newPatient = 0;
                    foreach($consult as $c){
                        if($c->patient->created_at->gte(now()->subdays(30))){
                            $newPatient++;
                        }else{
                            $oldPatient++;
                        }
                    }
                    // array_push($dateOfDays, $consult);
                    array_push($dateOfDays, [now()->subMonth($d)->locale('id_ID')->format('F Y'), count($consult), $oldPatient, $newPatient]);
                }

                return response()->json(['chartData' => $dateOfDays], 200);
            }


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

            // $userData = json_decode(auth()->user()->jamPraktek);


            return view('dokter.dashboard', [
                'title' => 'Dashboard',
                'consultOrder' => count($patients),
                'oldPatientCounts' => $patientsTime['old'],
                'newPatientCounts' => $patientsTime['new'],
                'practiceTime' => json_decode(auth()->user()->jamPraktek)
            ]);

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
                    'end' => $endTime->format('H:i')
                ];
                $dokter = Dokter::where('id', auth()->user()->id)->update(['jamPraktek' => json_encode($jamPraktek)]);
                return response(['message' => 'Data Updated Successfully'], 200);
            }catch(\Exception $e){
                return response($e->getMessage, 500);
            }
        }else{
            return response('Cannot Update Jam Praktek, Please check it again', 500);
        }
    }
}
