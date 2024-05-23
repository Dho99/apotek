@extends('layouts.main')
@section('styles')
    <style>
        .icon-copy {
            font-size: 15px;
        }
    </style>
@endsection
@section('content')
    <div class="row my-3">
        <div class="col-lg-4 col-md-6 col-12 mb-20">
            <div class="bg-white shadow rounded-lg">
                <div class="row pt-2 py-3 px-4">
                    <div class="col-12 d-flex">
                        <h6 class="d-flex align-items-center">Jumlah Pasien</h6>
                        <div class="badge bg-lightgreen ml-auto">{{ $thisYear->format('Y') }}</div>
                    </div>
                    <div class="col-12 my-2">
                        <h4>{{ $countPatients }} <span class="ml-3">Pasien</span></h4>
                    </div>
                    <div class="col-12 my-2">
                        <a href="/" class="text-success text-decoration-none weight-700">Lihat Detail</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 col-12 mb-20">
            <div class="bg-white shadow rounded-lg">
                <div class="row pt-2 py-3 px-4">
                    <div class="col-12 d-flex">
                        <h6 class="d-flex align-items-center">Jumlah Dokter</h6>
                        <div class="badge bg-lightgreen ml-auto">{{ $thisYear->format('Y') }}</div>
                    </div>
                    <div class="col-12 my-2">
                        <h4>{{ count($countDokter) }} <span class="ml-3">Dokter</span></h4>
                    </div>
                    <div class="col-12 my-2">
                        <a href="/" class="text-success text-decoration-none weight-700">Lihat Detail</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 col-12 mb-20">
            <div class="bg-white shadow rounded-lg">
                <div class="row pt-2 py-3 px-4">
                    <div class="col-12 d-flex">
                        <h6 class="d-flex align-items-center">Jumlah Kunjungan</h6>
                        <div class="badge bg-lightgreen ml-auto">{{ $thisYear->monthName . ' ' . $thisYear->format('Y') }}
                        </div>
                    </div>
                    <div class="col-12 my-2">
                        <h4>{{ $countKunjungan }} <span class="ml-3">Kunjungan</span></h4>
                    </div>
                    <div class="col-12 my-2">
                        <a href="/" class="text-success text-decoration-none weight-700">Lihat Detail</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="bg-white shadow rounded-lg">
                <div class="row pt-2 py-3 px-4">
                    <div class="col-12 d-flex">
                        <h5 class="d-flex align-items-center">Daftar Dokter Praktik</h5>
                        <div class="badge bg-lightgreen ml-auto">{{ $dateOfDay }}</div>
                    </div>
                    <div class="col-12 mt-4 mb-2 overflow-auto">
                        <table class="table w-100 border text-center">
                            <thead class="">
                                <tr class="">
                                    <th>No</th>
                                    <th>Nama Dokter</th>
                                    <th>Spesialis</th>
                                    <th>Jam Mulai</th>
                                    <th>Jam Akhir</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($countDokter as $dokter)
                                    @php
                                        $practiceTime = json_decode($dokter->jamPraktek);
                                        $start = $practiceTime->start;
                                        $end = $practiceTime->end;
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $dokter->nama }}</td>
                                        <td>{{ $dokter->kategoriDokter }}</td>
                                        <td id="startTime{{$dokter->kode}}">{{ $start }}</td>
                                        <td id="endTime{{$dokter->kode}}">{{ $end }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-success"
                                                onclick="editPracticeTime('{{ $dokter->kode }}','{{$dokter->nama}}','{{$start}}','{{$end}}')"><i
                                                    class="icon-copy dw dw-edit weight-700"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="practiceTimeModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-modal="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel">
                    Ubah Jam Praktik Dokter <br> <span id="doctorName"></span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    ×
                </button>
            </div>
            <div class="modal-body" id="practiceTimeForm">
                <div class="row px-0 mx-0 my-3">
                    <div class="input-group mb-3 col-lg-6 col-md-6 col-12">
                        <div class="input-group-prepend">
                        <label class="input-group-text border" for="inputGroupSelect01">Dari</label>
                        </div>
                    <input type="time" class="form-control" id="start" value="">
                    </div>
                    <div class="input-group mb-3 col-lg-6 col-md-6 col-12">
                        <div class="input-group-prepend">
                        <label class="input-group-text border" for="inputGroupSelect01">Sampai</label>
                        </div>
                    <input type="time" class="form-control" id="end" value="">
                    </div>
                    <div class="col-lg-4 col md-4 col-12 d-flex ml-auto">
                        <button class="btn btn-sm btn-success w-100" id="submitNewTime">Ubah Jam</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    @endsection

@push('scripts')
    <script>
        let startTime;
        let endTime;
        let dokterCode;

        const editPracticeTime = (id, name, start, end) => {
            $('#doctorName').text(`${name}`);
            $('#start').val(start);
            $('#end').val(end);
            startTime = start;
            endTime = end;
            dokterCode = id;
            $('#practiceTimeModal').modal('show');
        }

        $('#submitNewTime').on('click', (() => {
            let startData = $('#start').val();
            let endData = $('#end').val();
            let dataForm = {
                'start' : startData,
                'end' : endData,
                'code': dokterCode
            };
            asyncAjaxUpdate('{{route("updatePracticeTime")}}', 'PUT', dataForm).then((response) => {
                successAlert(response.message);
                $(`#startTime${dokterCode}`).text(startData);
                $(`#endTime${dokterCode}`).text(endData);
                $('#practiceTimeModal').modal('hide');
            }).catch((error) => {
                errorAlert(error);
                $('#start').val(startTime);
                $('#end').val(endTime);
            });
        }));

        $('#practiceTimeModal').on('hidden.bs.modal', (() => {
            $('#doctorName').text('');
            $('#start').val('');
            $('#end').val('');
        }));
    </script>
@endpush
