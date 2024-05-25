<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use Illuminate\Http\Request;

class ReportController extends Controller
{

    public function laporanKunjungan()
    {
        return view('administrator.laporan.kunjungan', [
            'title' => 'Laporan Kunjungan',
            'kunjungan' => Kunjungan::all()
        ]);
    }

}
