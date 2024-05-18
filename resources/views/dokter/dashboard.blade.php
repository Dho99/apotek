@extends('layouts.main')
{{-- @section('plugins')
    <link rel="stylesheet" href="{{asset('src/plugins/apexcharts/apexcharts.css')}}">
@endsection --}}
@section('content')
    <div class="row w-100">
        <div class="col-lg-4 col-md-12 col-6 py-2 px-4">
            <div class="bg-body shadow rounded row">
                <div class="col-3 bg-dark rounded-left">
                    s
                </div>
                <div class="col-9 py-3">
                    <h6 class="mb-2">Jumlah Pasien Konsultasi</h6>
                    <h3>{{ $consultOrder }}</h3>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-6 py-2 px-4">
            <div class="bg-body shadow rounded row">
                <div class="col-3 bg-dark rounded-left">
                    s
                </div>
                <div class="col-9 py-3">
                    <h6 class="mb-2">Jumlah Pasien Lama</h6>
                    <h3>{{ $oldPatientCounts }}</h3>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-6 py-2 px-4">
            <div class="bg-body shadow rounded row">
                <div class="col-3 bg-dark rounded-left">
                    s
                </div>
                <div class="col-9 py-3">
                    <h6 class="mb-2">Jumlah Pasien Baru</h6>
                    <h3>{{ $newPatientCounts }}</h3>
                </div>
            </div>
        </div>
        <div class="col-12 py-2 px-4">
            <div class="bg-body shadow rounded row p-3">
                <div class="col-12 d-flex p-0 m-0">
                    <h5>Grafik Pasien baru dan Lama</h5>
                    <select name="" id="getChartGraphBtn" class="form-control form-control-sm w-25 ml-auto">
                        <option value="week">1 Minggu</option>
                        <option value="month">30 Hari</option>
                        <option value="year">1 Tahun</option>
                    </select>
                </div>
                <div id="patientChart" class="w-100 mt-3"></div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            $('#getChartGraphBtn').val('week').change();
        });

        let chartDataValues = [];
        let chartDaysData = [];
        let chartLabelsData = [];
        let chartOldPatientsData = [];
        let chartNewPatientsData = [];

        $('#getChartGraphBtn').on('change', function() {
            $.ajax({
                method: 'GET',
                url: '{{ url()->current() }}',
                data: {
                    'time': $(this).val()
                },
                success: ((response) => {
                    chartDataValues = response.chartData;
                    chartDaysData = [];
                    chartLabelsData = [];
                    chartOldPatientsData = [];
                    chartNewPatientsData = [];
                    // console.log(chartDataValues);
                }),
                error: ((xhr, error) => {
                    console.log(xhr.responseText);
                }),
            }).done(() => {
                let cVal = chartDataValues.values();
                for (let value of cVal) {
                    chartDaysData.push(value[1]);
                    chartLabelsData.push(value[0]);
                    chartOldPatientsData.push(value[2]);
                    chartNewPatientsData.push(value[3]);
                }
                renderChart();
            });
        });


        function renderChart() {
            let chartData = {
                chart: {
                    type: 'area',
                    height: 350,
                    toolbar: {
                        show: false
                    },
                    zoom: {
                        enabled: false
                    },
                    stacked: true,
                },
                noData: {
                    text: 'Fetching...'
                },
                colors: ['#66DA26', '#2E93fA'],
                series: [],
                xaxis: {
                    categories: chartLabelsData,
                },
            }

            let chartGraph = new ApexCharts(document.querySelector('#patientChart'), chartData);
            chartGraph.render();
            // chartGraph.render();

            chartGraph.updateSeries([{
                    name: 'Pasien Lama',
                    data: chartOldPatientsData,
                },
                {
                    name: 'Pasien Baru',
                    data: chartNewPatientsData,
                }
            ]);
        }
    </script>
@endpush
