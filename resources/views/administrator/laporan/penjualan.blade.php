@extends('layouts.main')
@section('content')
    <div class="container bg-white mt-3 py-3 px-2 rounded row mx-0">
        <div class="col-12">
            <h5 class="text-center py-3 border-bottom">Menampilkan data <span id="titleStatus">1 Tahun Terakhir</span></h5>
        </div>
        <div class="col-lg-2 col-md-3 col-4 d-flex ml-auto mt-3">
            <button class="w-100 btn btn-sm btn-outline-success" onclick="openFilterModal()">Filter</button>
        </div>
        {{-- <div id="chart" class="my-4 col-12 border rounded py-3"></div> --}}
        {{-- </div>

<div class="container bg-white rounded py-3 px-2 mt-3"> --}}
        <div class="col-12">
            <table id="penjualanTable" class="table" style="width: 100%;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Penjualan</th>
                        <th>Customer</th>
                        <th>QTY</th>
                        <th>Gerus</th>
                        <th>Subtotal</th>
                        <th>Apoteker</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($penjualan as $p)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $p->kodePenjualan }}</td>
                            <td>{{ $p->patientId }}</td>
                            <td>{{ $p->produkId }}</td>
                            <td>{{ $p->isGerus }}</td>
                            <td>{{ $p->subtotal }}</td>
                            <td>{{ $p->userId }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
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
                                            $selectMonth = [];
                                            foreach ($monthRange as $mo) {
                                                array_push(
                                                    $selectMonth,
                                                    \Illuminate\Support\Carbon::now()->subMonth($mo)->monthName,
                                                );
                                            }
                                        @endphp
                                        @foreach ($selectMonth as $key => $m)
                                            <option value="{{ $monthRange[$key] }}" month="{{ $m }}">
                                                {{ $m }}</option>
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
    @php
        $oldestYearData = \App\Models\Penjualan::first();
        if(isset($oldestYearData)){
            $firstYearData = $oldestYearData->created_at->format('Y');
        }else{
            $firstYearData = now()->format('Y');
        }
        $parsedOldestYearData = \Illuminate\Support\Carbon::parse($firstYearData)->format('Y');
    @endphp
@endsection
@push('scripts')
    <script>
        let method;

        $(function() {
            $('#penjualanTable').DataTable({
                responsive: true,
                destroy: true,
                layout: {
                    top: {
                        buttons: ['csv', 'excel', 'pdf', 'print']
                    },
                    topEnd: {
                        search: {
                            placeholder: 'Type search here'
                        }
                    },
                }
            });
            $('#filterByMonth, #filterByYear').hide();
            yearPickerOptions();
            method = $('#filterMode').val();
        });

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

        const yearPickerOptions = () => {
            let currentYear = new Date().getFullYear();
            for (let i = '{{ $parsedOldestYearData }}'; i <= currentYear; i++) {
                $('#yearPicker, #yearMonthPicker').append(`
                    <option value="${i}">${i}</option>
                `);
            }
        };

        function openFilterModal() {
            $('#filterModal').modal('show');
        }

        const filterLaporan = () => {
            let data = {
                filterMethod: method,
            };

            let isGoFetch;
            let tableTitle = $('#titleStatus');
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
                    tableTitle.text(`${formatDate(methodParams.startFilter)} - ${formatDate(methodParams.endFilter)}`);
                } else {
                    callError();
                }
            } else if (method == 'byMonth') {
                const month = $('#monthFilter').val();
                const year = $('#yearMonthPicker').val();
                const optionSelected = $('#monthFilter option:selected').attr('month');
                if (month !== '' || year !== '') {
                    const methodParams = {
                        monthFilter: month,
                        yearFilter: year
                    };
                    Object.assign(data, methodParams);
                    isGoFetch = true;
                    tableTitle.text(`Bulan ${optionSelected}`);
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
                asyncAjaxUpdate('{{ route('laporanPenjualan') }}', 'GET', data).then((response) => {
                    updatePageValues(response);
                }).catch((error) => {
                    errorAlert(error);
                });
            }

        }

        const updatePageValues = (params) => {
            console.log(params);
            $('#filterModal').modal('hide');
            if (typeof params.data.error !== 'undefined') {
                errorAlert(params.data.error);
            } else {
                // updateChart(params);
                $('#kunjunganTable').DataTable().destroy();
                $('#kunjunganTable').DataTable({
                    responsive: true,
                    destroy: true,
                    layout: {
                        top: {
                            buttons: ['csv', 'excel', 'pdf', 'print']
                        },
                        topEnd: {
                            search: {
                                placeholder: 'Type search here'
                            }
                        },
                    },
                    data: params.data,
                    columns: [{
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
                                let dokterName;
                                if (row.dokterId === null) {
                                    dokterName = 'Belum Ditindak'
                                } else {
                                    dokterName = row.dokter.nama;
                                }
                                return dokterName;
                            }
                        },
                    ]

                });

            }
        }

        const callError = () => {
            errorAlert('Silakan masukkan data yang diperlukan dengan benar');
        };
    </script>
@endpush
