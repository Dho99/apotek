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
                                <option class="font-weight-normal" value="byDate">Berdasarkan Tanggal</option>
                                <option class="font-weight-normal" value="byMonth">Berdasarkan Bulan</option>
                                <option class="font-weight-normal" value="byYear">Berdasarkan Tahun</option>
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
                            <div id="filterByMonth" class="row mt-3">
                                <div class="form-group col-6">
                                    <label for="">Bulan</label>
                                    <select name="" id="monthFilter" class="form-control">
                                        @php
                                            $monthRange = range(0, 11);
                                        @endphp
                                        @foreach ($monthRange as $m)
                                            <option value="{{ $m }}">
                                                {{ \Illuminate\Support\Carbon::now()->subMonth($m)->monthName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-6">
                                    <label for="">Tahun</label>
                                    <select name="" id="yearMonthPicker" class="form-control">
                                    </select>
                                </div>
                            </div>
                            <div id="filterByYear" class="row mt-3">
                                <div class="col-12">
                                    <label for="">Tahun</label>
                                    <select name="" id="yearPicker" class="form-control">
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success d-flex ml-auto" onclick="filterLaporan()">Filter</button>
                </div>
            </div>
        </div>
    </div>


    <div class="container bg-white mt-3 py-3 px-2 rounded row mx-0">
        <div class="col-6">
            <h5>Menampilkan data sejak 1 Tahun Terakhir</h5>
        </div>
        <div class="col-lg-2 col-md-3 col-4 d-flex ml-auto">
            <button class="w-100 btn btn-sm btn-outline-success" onclick="openFilterModal()">Filter</button>
        </div>
        <div id="chart" class="my-4 col-12 border rounded py-3"></div>
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
    @php
        $oldestYearData = \App\Models\Kunjungan::first()->pluck('created_at');
        $parsedOldestYearData = \Illuminate\Support\Carbon::parse($oldestYearData[0])->format('Y');
    @endphp
@endsection
@push('scripts')
    <script src="{{ asset('js/utility.js') }}"></script>
    <script>
        let method;

        $(function() {
            renderChart();
            $('#kunjunganTable').DataTable({
                responsive: true,
                destroy: true
            });
            $('#filterByMonth, #filterByYear').hide();
            yearPickerOptions();
            method = $('#filterMode').val();
        });

        const yearPickerOptions = () => {
            let currentYear = new Date().getFullYear();
            for (let i = '{{ $parsedOldestYearData }}'; i <= currentYear; i++) {
                $('#yearPicker, #yearMonthPicker').append(`
                    <option value="${i}">${i}</option>
                `);
            }
        };


        const getChartTitle = () => {
            let title = {
                align: 'center'
            };

            let text = {};


            if (typeof startDate === 'undefined' || typeof endDate === 'undefined') {
                text = {
                    text: 'Grafik Kunjungan sejak 1 Tahun terakhir'
                };
            } else {
                text = {
                    text: `Grafik Kunjungan sejak ${formatDate(startDate)} hingga ${formatDate(endDate)}`
                }
            }
            Object.assign(title, text);
            return title;
        }

        const openFilterModal = () => {
            $('#filterModal').modal('show');
        }

        $('#filterMode').on('change', function() {
            $('#filterByDate, #filterByMonth, #filterByYear').hide();
            let selectedValue = $(this).val();
            method = selectedValue;
            if (selectedValue === 'byDate') {
                $('#filterByDate').show();
            } else if (selectedValue === 'byMonth') {
                $('#filterByMonth').show()
            } else {
                $('#filterByYear').show();
            }
        });


        const filterLaporan = () => {
            let data = {
                filterMethod: method,
            };

            let isGoFetch;

            if (method == 'byDate') {
                const startDate = $('input#start').val();
                const endDate = $('input#end').val();
                if (startDate !== '' || endDate !== '') {
                    const methodParams = {
                        startFilter: startDate,
                        endFilter: endDate,
                    };
                    Object.assign(data, methodParams);
                    isGoFetch = true;
                } else {
                    callError();
                }
            } else if (method == 'byMonth') {
                const month = $('#monthFilter').val();
                const year = $('#yearMonthFilter').val();
                if (month !== '' || year !== '') {
                    const methodParams = {
                        monthFilter: month,
                        yearFilter: year
                    };
                    Object.assign(data, methodParams);
                    isGoFetch = true;
                } else {
                    callError();
                }
            } else if (method == 'byYear') {
                const year = $('#yearPicker').val();
                if (year !== '') {
                    const methodParams = {
                        yearFilter: year
                    };
                    Object.assign(data, methodParams);
                    isGoFetch = true;
                } else {
                    callError();
                }
            } else {
                errorAlert('Silakan pilih metode untuk Filter');
            }

            if (isGoFetch) {
                asyncAjaxUpdate('{{ route('filterKunjungan') }}', 'GET', data).then((response) => {
                    updatePageValues(response);
                }).catch((error) => {
                    errorAlert(error);
                });
            }

        }

        const callError = () => {
            errorAlert('Silakan masukkan data yang diperlukan dengan benar');
        };

        function renderChart() {
            asyncAjaxUpdate('{{ url()->current() }}', 'GET', null).then((response) => {
                let options = {
                    title: getChartTitle(),
                    chart: {
                        type: 'area',
                        height: '400',
                        stacked: false,
                        zoom: false
                    },
                    stroke: {
                        curve: 'smooth'
                    },
                    series: [{
                        name: 'Jumlah Kunjungan',
                        data: response.yearKunjungan
                    }, {
                        name: 'Pasien Lama',
                        data: response.oldPatient
                    }, {
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

        const updatePageValues = (params) => {
            $('#filterModal').modal('hide');
            if (typeof params.data.error !== 'undefined') {
                errorAlert(params.data.error);
            } else {
                updateTable('#kunjunganTable', params.data, [{
                        title: "No",
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        title: "No. Rekam Medis",
                        data: "patient.no_rekam_medis"
                    },
                    {
                        title: "Nama",
                        data: "patient.nama"
                    },
                    {
                        title: "Gender",
                        data: null,
                        render: function(data, type, row) {
                            return `${row.gender === '1' ? 'Pria' : 'Wanita'}`
                        }
                    },
                    {
                        title: "Status",
                        data: null,
                        render: function(data, type, row) {
                            if (row.dokterId !== 'null') {
                                return 'Belum Diproses';
                            } else {
                                return 'Sudah Diproses';
                            }
                        }
                    },
                    {
                        title: "Diproses Oleh",
                        data: null,
                        render: function(data, type, row) {
                            if (row.dokterId !== 'null') {
                                return row.dokter.nama;
                            } else {
                                return 'Belum Ditindak';
                            }
                        }
                    },
                ])
            }


        }
    </script>
@endpush
