<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Alert;

class ReportController extends Controller
{
    public function laporanKunjungan(Request $request)
    {
        if ($request->ajax()) {
            $monthRange = range(11, 0);
            $monthName = [];
            $yearKunjungan = [];
            $oldPatient = [];
            $newPatient = [];
            foreach ($monthRange as $month) {
                $getData = Kunjungan::whereYear('created_at', '>=', now()->subYear())->whereMonth('created_at', Carbon::now()->subMonth($month))->with('patient')->get();
                $getMonthName = now()->submonth($month)->monthName . ' ' . now()->submonth($month)->format('Y');
                $sumNewPatient = 0;
                $sumOldPatient = 0;
                foreach ($getData as $d) {
                    if (Carbon::parse($d->patient->created_at)->gte(now()->subdays(7))) {
                        $sumNewPatient += 1;
                    } else {
                        $sumOldPatient += 1;
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
                'newPatient' => $newPatient,
            ]);
        }
        // dd($yearKunjungan, $monthName, $oldPatient, $newPatient);
        return view('administrator.laporan.kunjungan', [
            'title' => 'Laporan Kunjungan',
            'kunjungan' => Kunjungan::whereYear('created_at', '>=', now()->subYear())->with('dokter', 'patient')->get(),
        ]);
    }

    public function filter(Request $request)
    {
        if ($request->ajax()) {
            $filterMode = $request->filterMethod;
            if ($filterMode == 'byDate') {
                $visitsData = $this->filterByDate(['startDate' => $request->startFilter, 'endDate' => $request->endFilter]);
            } elseif ($filterMode == 'byMonth') {
                $visitsData = $this->filterByMonth(['month' => $request->monthFilter, 'year' => $request->yearFilter]);
            } elseif ($filterMode == 'byYear') {
                $visitsData = $this->filterByYear($request->yearFilter);
            }
            if (!isset($visitsData['error'])) {
                $dataLabel = [];
                if ($filterMode == 'byDate') {
                    foreach ($visitsData as $d) {
                        $timeOfVisit = $d->created_at;
                        $label = [
                            'date' => $timeOfVisit->format('d'),
                            'month' => $timeOfVisit->format('m'),
                            'year' => $timeOfVisit->format('Y'),
                            'monthName' => $timeOfVisit->monthName,
                        ];

                        if (!in_array($label, $dataLabel)) {
                            array_push($dataLabel, $label);
                        }
                    }
                } else {
                    foreach ($visitsData as $d) {
                        $timeOfVisit = $d->created_at;
                        $label = [
                            'month' => $timeOfVisit->format('m'),
                            'year' => $timeOfVisit->format('Y'),
                            'monthName' => $timeOfVisit->monthName,
                        ];

                        if (!in_array($label, $dataLabel)) {
                            array_push($dataLabel, $label);
                        }
                    }
                }

                $countData = [];
                foreach ($dataLabel as $label) {
                    $visits = Kunjungan::whereMonth('created_at', $label['month'])
                        ->whereYear('created_at', $label['year'])
                        ->count();
                    array_push($countData, $visits);
                }

                $monthName = [];
                if ($filterMode == 'byDate') {
                    foreach ($dataLabel as $d) {
                        $labelsArray = [$d['date'], $d['monthName'], $d['year']];
                        $merged = implode(' ', $labelsArray);
                        array_push($monthName, $merged);
                    }
                } else {
                    foreach ($dataLabel as $d) {
                        $merged = $d['monthName'] . ' ' . $d['year'];
                        array_push($monthName, $merged);
                    }
                }

                $visitsData->load('patient', 'dokter');
                return response()->json(['data' => $visitsData, 'label' => $monthName, 'countVisits' => $countData], 200);
            } else {
                return response($visitsData['error'], 500);
            }
        }
    }

    protected function filterByDate($params)
    {
        $startDate = Carbon::parse($params['startDate'])->startOfDay();
        $endDate = Carbon::parse($params['endDate'])->endOfDay();
        switch($startDate){
            case $startDate->gte($endDate):
                return ['error' => 'Data StartDate tidak boleh mendahului'];
                break;
            case $endDate->gte($startDate->addMonth(3)):
                return ['error' => 'Data Filtering tidak boleh lebih dari 60 Hari'];
                break;
            default:
                $getVisitsData = Kunjungan::whereBetween('created_at', [Carbon::parse($params['startDate'])->startOfDay(), Carbon::parse($params['endDate'])->endOfDay()])->get();
                return $getVisitsData;

        }
    }

    protected function filterByMonth($params)
    {
        $month = now()
            ->subMonth($params['month'])
            ->format('m');
        $year = $params['year'];
        $getVisitsData = Kunjungan::whereMonth('created_at', $month)->whereYear('created_at', $year)->get();
        return $getVisitsData;
    }

    protected function filterByYear($params)
    {
        $getVisitsData = Kunjungan::whereYear('created_at', $params)->get();
        return $getVisitsData;
    }
}
