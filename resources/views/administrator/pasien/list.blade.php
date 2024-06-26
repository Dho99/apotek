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
        <table id="dokterTable" class="table" style="width: 100%;">
            <thead>
                <tr>
                    <th>No</th>
                    <th>No Rekam Medis</th>
                    <th>Nama Pasien</th>
                    <th>Gender</th>
                    <th>Tanggal Lahir</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody class="">
                @foreach ($pasiens as $d)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="text-center">{{ $d->no_rekam_medis }}</td>
                        <td>{{ $d->nama }}</td>
                        <td>{{ $d->gender = 1 ? 'Pria' : 'Wanita' }}</td>
                        <td>{{ \Illuminate\Support\Carbon::parse($d->tanggal_lahir)->format('d F Y') }}</td>
                        <td class="">
                            <div class="d-flex">
                                <a href="{{route('pasien.show',[$d->id])}}" class="btn btn-sm btn-secondary"><i
                                        class="icon-copy dw dw-edit1 d-flex align-items-center"></i></a>
                                <form class="d-inline-flex" method="POST" action="{{route('pasien.destroy',[$d->id])}}" onclick="return confirm('Apakah anda yakin menghapus data ini ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"><i
                                        class="icon-copy dw dw-trash d-flex align-items-center"></i></button>
                                </form>
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
