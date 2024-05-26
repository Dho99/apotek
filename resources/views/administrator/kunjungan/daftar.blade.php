@extends('layouts.main')
@section('content')
    <div class="container bg-white rounded py-3 px-2 mt-3">
        <table id="kunjunganTable" class="table" style="width: 100%;">
            <thead>
                <tr>
                    <th>No</th>
                    <th>No. Rekam Medis</th>
                    <th>Nama</th>
                    <th>Status</th>
                    <th>Detail</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($kunjungan as $k)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="text-center">{{ $k->patient->no_rekam_medis }}</td>
                        <td>{{ $k->patient->nama }}</td>
                        <td>{{ isset($k->dokterId) ? 'Sudah Ditangani' : 'Belum Ditindak'}}</td>
                        <td class="">
                            <div class="d-flex">
                                <button class="btn btn-sm btn-secondary" onclick="getDetails('{{ $k->id }}')"><i
                                        class="icon-copy dw dw-information"></i></button>
                                <form action="{{ route('kunjungan.destroy', [$k->id]) }}" id="{{ $k->id }}"
                                    method="POST">
                                    @method('DELETE')
                                    @csrf
                                    <button type="button" class="btn btn-sm btn-danger"
                                        onclick="askForDelete('{{ $k->id }}')"><i
                                            class="icon-copy dw dw-trash1"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="modal fade" id="kunjunganModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Detail Kunjungan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row d-flex">
                        <div class="col-6 form-group">
                            <label for="">No Rekam Medis</label>
                            <input type="text" class="form-control" id="noRm">
                        </div>
                        <div class="col-6 form-group">
                            <label for="">Gender</label>
                            <input type="text" class="form-control" id="gender">
                        </div>
                        <div class="col-6 form-group">
                            <label for="">Nama Pasien</label>
                            <input type="text" id="namaPasien" class="form-control">
                        </div>
                        <div class="col-6 form-group">
                            <label for="">Nama Dokter</label>
                            <input type="text" id="namaDokter" class="form-control">
                        </div>
                        <div class="col-6 form-group">
                            <label for="">Keluhan</label>
                            <textarea id="keluhan" class="form-control" cols="30" rows="10"></textarea>
                        </div>
                        <div class="col-6 form-group">
                            <label for="">Diagnosa</label>
                            <textarea id="diagnosa" class="form-control" cols="30" rows="10"></textarea>
                        </div>
                        <div class="col-12 form-group">
                            <label for="">Tindakan</label>
                            <textarea id="tindakan" class="form-control" cols="30" rows="10"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex">
                    <button type="button" class="btn btn-secondary ms-auto" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $('#kunjunganTable').DataTable({
            responsive: {
                details: true
            }
        });


        const getDetails = (id) => {
            const url = `{{ url()->current() }}/${id}`;
            asyncAjaxUpdate(url, 'GET', null).then((result) => {
                const res = result.result;
                $('.modal-body').find('input, textarea').attr('readonly','readonly');
                let dokterName;
                if(res.dokterId !== null){
                    dokterName = `${res.dokter.kode} - ${res.dokter.nama}`;
                }else{
                    dokterName = `Belum ditindak`;
                }
                $('#noRm').val('').val(`${res.patient.no_rekam_medis}`);
                $('#gender').val('').val(`${res.patient.gender === '1' ? 'Pria' : 'Wanita'}`);
                $('#namaPasien').val('').val(`${res.patient.kode} - ${res.patient.nama}`);
                $('#namaDokter').val('').val(`${dokterName}`);
                $('#keluhan').val('').val(`${res.keluhan}`);
                $('#diagnosa').val('').val(`${res.diagnosa.length > 1 ? res.diagnosa : 'Belum Ditindak'}`);
                $('#tindakan').val('').val(`${res.tindakan.length > 1 ? res.tindakan : 'Belum Ditindak'}`);
                $('#kunjunganModal').modal('show');
            }).catch((error) => {
                errorAlert(error);
            });
        }

        const askForDelete = (id) => {
            decisionAlert('Konfirmasi', 'Apakah anda yakin akan menghapus record ini ?',
                'Data Kunjungan tetap Disimpan').then((result) => {
                $(`form#${id}`).submit();
            }).catch((message) => {
                console.log(message);
            });
        }
    </script>
@endpush
