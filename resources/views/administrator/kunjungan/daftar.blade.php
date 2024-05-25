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
                    <th>Diagnosa</th>
                    <th>Detail</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($kunjungan as $k)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$k->no_rekam_medis}}</td>
                        <td>{{$k->gender == 1 ? 'Pria' : 'Wanita'}}</td>
                        <td></td>
                        <td>
                            <button class="btn btn-sm btn-secondary"><i class="icon-copy dw dw-information"></i></button>
                            <form action="{{route('kunjungan.destroy',[$d->id])}}" id="{{$d->id}}">
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-danger d-flex ms-auto" onclick="askForDelete('{{$d->id}}')"><i class="icon-copy dw dw-trash1"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection
@push('scripts')
    <script>
        $('#kunjunganTable').DataTable({
            responsive: true
        });

        const askForDelete = (id) => {
            decisionAlert('Konfirmasi','Apakah anda yakin akan menghapus record ini ?').then((result) => {
                deleteRecord(id);
            }).catch((error) => {
                errorAlert('konz');
            });
        }

        const deleteRecord = (id) => {
            $(`form#${id}`).submit();
        }
    </script>
@endpush
