@extends('layouts.main')
@section('content')
    <div class="row w-100">
        <div class="col-lg-4 col-md-12 col-6 py-2 px-4">
            <div class="bg-body shadow rounded row">
                <div class="col-3 bg-dark rounded-left">
                    s
                </div>
                <div class="col-9 py-3">
                    <h6 class="mb-2">Jumlah Pasien Konsultasi</h6>
                    <h3>{{count($consultOrder)}}</h3>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-6 py-2 px-4">
            <div class="bg-body shadow rounded row">
                <div class="col-3 bg-dark rounded-left">
                    s
                </div>
                <div class="col-9 py-3">
                    <h6 class="mb-2">Jumlah Pasien Lama</h6>
                    <h3>{{count($patientCounts['oldPatient'])}}</h3>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-6 py-2 px-4">
            <div class="bg-body shadow rounded row">
                <div class="col-3 bg-dark rounded-left">
                    s
                </div>
                <div class="col-9 py-3">
                    <h6 class="mb-2">Jumlah Pasien Baru</h6>
                    <h3>{{count($patientCounts['newPatient'])}}</h3>
                </div>
            </div>
        </div>
        <div class="col-12 py-2 px-4">
            <div class="bg-body shadow rounded row">
                <div class="col-3 bg-dark rounded-left">
                    s
                </div>
                <div class="col-9 py-3">
                    <h6 class="mb-2">Grafik Konsultasi Pasien Baru dan Lama</h6>
                    <h3>{{count($patientCounts['newPatient'])}}</h3>
                </div>
            </div>
        </div>

    </div>
@endsection
