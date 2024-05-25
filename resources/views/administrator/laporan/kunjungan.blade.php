@extends('layouts.main')
@section('content')

<div class="container bg-white rounded py-3 px-2 mt-3">
    <table id="kunjunganTable" class="table" style="width: 100%;">
        <thead>
            <tr>
                <th>No</th>
                <th>No. Rekam Medis</th>
                <th>Nama</th>
                <th>Gender</th>
                <th>Keluhan</th>
                <th>Detail</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($kunjungan as $k)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$k->no_rekam_medis}}</td>
                    <td>{{$k->gender == 1 ? 'Pria' : 'Wanita'}}</td>
                    <td>{{$k}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>


@endsection
@push('scripts')
<script></script>
@endpush
