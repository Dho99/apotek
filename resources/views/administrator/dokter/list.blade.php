@extends('layouts.main')
@section('styles')
    <style>
        .icon-copy {
            font-size: 17px;
        }
    </style>
@endsection
@section('content')
    <div class="container bg-white rounded py-3 px-2 mt-3">
        <table id="dokterTable" class="table text-center" style="width: 100%;">
            <thead>
                <tr>
                    <th data-dt-order="disable">No</th>
                    <th>Nama Dokter</th>
                    <th>Spesialis Dokter</th>
                    <th colspan="2" data-dt-order="disable" class="text-center">Jam Praktek</th>
                    <th>Jumlah Konsultasi</th>
                    <th>Aksi</th>
                </tr>
                <tr>
                    <th colspan="3" data-dt-order="disable"></th>
                    <th>Mulai</th>
                    <th>Akhir</th>
                    <th colspan="2" data-dt-order="disable"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dokters as $d)
                    {{-- @dd($d) --}}
                    @php
                        $practiceTime = json_decode($d->jamPraktek);
                        $start = $practiceTime->start;
                        $end = $practiceTime->end;
                        $countKonsultasi = \App\Models\Kunjungan::where('dokterId', $d->id)->count();
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $d->nama }}</td>
                        <td>{{ $d->kategoriDokter }}</td>
                        <td>{{ $start }}</td>
                        <td>{{ $end }}</td>
                        <td>{{ $countKonsultasi . ' Konsultasi' }} </td>
                        <td class="">
                            <div class="d-flex">
                                <a href="{{route('dokter.show',[$d->id])}}" class="btn btn-sm btn-secondary"><i
                                        class="icon-copy dw dw-edit1 d-flex align-items-center"></i></a>
                                <a class="btn btn-sm btn-danger ms-auto"><i
                                        class="icon-copy dw dw-trash d-flex align-items-center"></i></a>
                            </div>
                        </td>
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
                responsive: true
            });
        });
    </script>
@endpush
