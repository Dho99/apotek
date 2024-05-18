@extends('layouts.main')
@section('styles')
 <style>
    .rounded-left{
        background-color: #7dd0a4 ;
    }
 </style>
@endsection
@section('content')
    <div class="row w-100">
        <div class="col-12 py-2 px-4">
            <div class="bg-body shadow rounded row">
                <div class="col-3 rounded-left d-flex ">
                    <h1 class="icon-copy dw dw-time-management text-light fw-bold m-auto"></h1>
                </div>
                <div class="col-9 py-3">
                    <h6 class="mb-2">Jam Praktek Anda </h6>
                    <div class="row w-100 my-3">
                        <div class="input-group mb-3 col-lg-6 col-md-6 col-12">
                            <div class="input-group-prepend">
                            <label class="input-group-text border" for="inputGroupSelect01">Dari</label>
                            </div>
                        <input type="time" class="form-control" id="start" value="{{$practiceTime->start}}" disabled>
                        </div>
                        <div class="input-group mb-3 col-lg-6 col-md-6 col-12">
                            <div class="input-group-prepend">
                            <label class="input-group-text border" for="inputGroupSelect01">Sampai</label>
                            </div>
                        <input type="time" class="form-control" id="end" value="{{$practiceTime->end}}" disabled>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-12 py-2 px-4">
            <div class="bg-body shadow rounded row">
                <div class="col-3 rounded-left d-flex ">
                    <h1 class="icon-copy dw dw-user-3 text-light fw-bold m-auto"></h1>
                </div>
                <div class="col-9 py-3">
                    <h6 class="mb-2">Jumlah Pasien Konsultasi</h6>
                    <h3>{{ $consultOrder }}</h3>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-12 py-2 px-4">
            <div class="bg-body shadow rounded row">
                <div class="col-3 rounded-left d-flex">
                    <h1 class="icon-copy dw dw-id-card2 m-auto text-light"></h1>
                </div>
                <div class="col-9 py-3">
                    <h6 class="mb-2">Jumlah Pasien Lama</h6>
                    <h3>{{ $oldPatientCounts }}</h3>
                </div>
            </div>
        </div>
        <div class="col-12 py-2 px-4">
            <div class="bg-body shadow rounded row">
                <div class="col-3 rounded-left d-flex">
                    <h1 class="icon-copy dw dw-add-user m-auto text-light m-auto"></h1>
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


        // $('#updatePracticeTime').on('click', function(){
        //     let startData = $('#start').val();
        //     let endData = $('#end').val();
        //     // console.log(startData, endData)
        //     let dataForm = {
        //         'start' : startData,
        //         'end' : endData
        //     };
        //     asyncAjaxUpdate('{{route("updatePracticeTime")}}', 'PUT', dataForm).then((response) => {
        //         successAlert(response.message);
        //     }).catch((error) => {
        //         errorAlert(error);
        //         $('#start').val('{{$practiceTime->start}}');
        //         $('#end').val('{{$practiceTime->end}}');
        //     });
        // });


    </script>
@endpush
