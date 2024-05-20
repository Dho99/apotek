@extends('layouts.main')
@section('content')
    <div class="container bg-white rounded py-3 px-2 mt-3">
        <table id="dokterTable" class="table text-center w-100">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Dokter</th>
                    <th>Spesialis Dokter</th>
                    <th colspan="2">Jam Praktek</th>
                    <th>Pasien Konsultasi</th>
                    <th>Aksi</th>
                </tr>
                <tr>
                    <th colspan="3"></th>
                    <th>Mulai</th>
                    <th>Akhir</th>
                    <th colspan="2"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dokters as $d)
                @php
                    $practiceTime = json_decode($d->jamPraktek);
                    $start = $practiceTime->start;
                    $end = $practiceTime->end;
                @endphp
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$d->nama}}</td>
                        <td>{{$d->kategoriDokter}}</td>
                        <td>{{$start}}</td>
                        <td>{{$end}}</td>
                        <td>consult</td>
                        <td>KONZX</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
@push('scripts')
    <script>
        $(() => {
            $('#dokterTable').DataTable({
                responsive: true,
            });
        });
        // asyncAjaxUpdate('{{url()->current()}}','GET',null).then((response) => {
        //     updateTable('#dokterTable', response.dokters, [
        //         {
        //             title: "No",
        //             data: null,
        //             render: ((data, type, row, meta) => {
        //                 return meta.rows + meta.settings._iDisplayStart + 1;
        //             }),
        //         },
        //         {
        //             title: "Nama Dokter",
        //             data: "kategoriDokter"
        //         },
        //         {
        //             title: "Mulai",
        //             data: "jamPraktek"
        //         },
        //         {

        //         }
        //     ])
        // }).catch((error) => {
        //     errorAlert(error.message);
        // });
    </script>
@endpush
