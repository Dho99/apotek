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
                    <h3>{{$consultOrder}}</h3>
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
                    <h3>{{$oldPatientCounts}}</h3>
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
                    <h3>{{$newPatientCounts}}</h3>
                </div>
            </div>
        </div>
        <div class="col-12 py-2 px-4">
            <div class="bg-body shadow rounded row p-3">
              <h5>Grafik Pasien baru dan Lama</h5>
              <div id="patientChart" class="w-100 mt-3"></div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
<script>


    $(function(){
        let chartDataValues;

        let chartDaysData = [];
        let chartLabelsData = [];
        $.ajax({
            method: 'GET',
            url: '{{url()->current()}}',
            success: ((response) => {
                chartDataValues = (response.chartData);
            }),
            error: ((xhr, error) => {
                console.log(xhr.responseText);
            })
        }).done(() => {
            let cVal = chartDataValues.values();
            for(let value of cVal){
                chartDaysData.push(value[1]);
                chartLabelsData.push(value[0]);
            }
            console.log(chartLabelsData);
            console.log(chartDaysData);
            chartGraph.render();
        });

        let chartData = {
            chart: {
                type: 'area',
                height: 350,
            },
            stroke:{
                curve: 'smooth'
            },
            series: [
            {
                name: 'Pasien Lama',
                data: chartDaysData
            },
            {
                name: 'Pasien Baru'
            }
        ],
            xaxis: {
                categories: chartLabelsData
            }
        }

        let chartGraph = new ApexCharts(document.querySelector('#patientChart'), chartData);
        // chartGraph.render();
    });

</script>

@endpush
