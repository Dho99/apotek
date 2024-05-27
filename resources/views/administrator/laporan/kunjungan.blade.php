@extends('layouts.main')
@section('content')
    <div class="modal fade" id="filterModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Filter Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row d-flex w-100 mx-0 px-0">
                        <div class="col-12">
                            <label for="">Pilih Mode</label>
                            <select name="" class="form-control" id="filterMode">
                                <option class="h5 font-weight-normal" value="byDate">Berdasarkan Tanggal</option>
                                <option class="h5 font-weight-normal" value="byMonth" selected>Berdasarkan Bulan</option>
                                <option class="h5 font-weight-normal" value="byYear">Berdasarkan Tahun</option>
                            </select>
                            <div id="filterByDate" class="row mt-3">
                                <div class="form-group col-6">
                                    <label for="">Dari</label>
                                    <input type="date" name="" id="start" class="form-control">
                                </div>
                                <div class="form-group col-6">
                                    <label for="">Sampai</label>
                                    <input type="date" name="" id="end" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary d-flex ml-auto">Understood</button>
                </div>
            </div>
        </div>
    </div>


    <div class="container bg-white mt-3 py-3 px-2 rounded row mx-0">
        <div class="col-6">
            <h5>Menampilkan data sejak 1 Tahun Terakhir</h5>
        </div>
        {{-- <div class="col-3 d-flex ml-auto">
            <button class="w-100 btn btn-sm btn-outline-success" onclick="openFilterModal()">Filter</button>
        </div> --}}
        <div id="chart" class="my-4 col-12 border rounded"></div>
        {{-- </div>

    <div class="container bg-white rounded py-3 px-2 mt-3"> --}}
        <div class="col-12">
            <table id="kunjunganTable" class="table" style="width: 100%;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No. Rekam Medis</th>
                        <th>Nama</th>
                        <th>Gender</th>
                        <th>Status</th>
                        <th>Diproses Oleh</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kunjungan as $k)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="text-center">{{ $k->patient->no_rekam_medis }}</td>
                            <td>{{ $k->patient->nama }}</td>
                            <td>{{ $k->gender == 1 ? 'Pria' : 'Wanita' }}</td>
                            <td>{{ isset($k->dokterId) ? 'Sudah Ditangani' : 'Belum Diproses' }}</td>
                            <td>{{ isset($k->dokterId) ? $k->dokter->nama : 'Belum Diproses' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(function() {
            renderChart();
            $('#kunjunganTable').DataTable({
                responsive: true,
            });
        });

        function renderChart(){
            asyncAjaxUpdate('{{url()->current()}}','GET',null).then((response) => {
                let options = {
                    chart: {
                        toolbar:{
                            show: false,
                        },
                        type: 'area',
                        height: '400',
                        stacked: false,
                    },
                    stroke: {
                        curve: 'smooth'
                    },
                    series: [
                    {
                        name: 'Jumlah Kunjungan',
                        data: response.yearKunjungan
                    },{
                        name: 'Pasien Lama',
                        data: response.oldPatient
                    },{
                        name: 'Pasien Baru',
                        data: response.newPatient
                    }],
                    labels: response.monthName
                }

                var chart = new ApexCharts(document.querySelector("#chart"), options);
                chart.render();
            }).catch((error) => {
                errorAlerrt(error);
            });
        }

    </script>
@endpush
