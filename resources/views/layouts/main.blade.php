<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title>PharmaPal | {{ $title }}</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <!-- CSS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/core.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/icon-font.min.css') }}" />
    <link rel="stylesheet" type="text/css"
        href="{{ asset('src/plugins/datatables/css/dataTables.bootstrap4.min.css') }}" />
    <link rel="stylesheet" type="text/css"
        href="{{ asset('src/plugins/datatables/css/responsive.bootstrap4.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/style.min.css') }}" />
    <link rel="stylesheet" type="text/css"
        href="{{ asset('src/plugins/datatables/css/dataTables.bootstrap4.min.css') }}" />
    <link rel="stylesheet" type="text/css"
        href="{{ asset('src/plugins/datatables/css/responsive.bootstrap4.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('src/plugins/sweetalert2/sweetalert2.css') }}" />
    {{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> --}}
    {{-- SwiperCDN --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />

</head>

<body class="">
    @include('partials.navbar')
    @include('partials.sidebar')
    @include('partials.notification-bar')
    <div class="mobile-menu-overlay"></div>


    <div class="main-container noprint">
        @yield('content')

        <div class="container">
        </div>
    </div>






    <div class="modal fade" id="logout-modal" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body text-center font-18">
                    <h4 class="padding-top-30 mb-30 weight-500">
                        Apakah Anda Yakin akan Log Out ?
                    </h4>
                    <div class="padding-bottom-30 row" style="max-width: 170px; margin: 0 auto">
                        <div class="col-6">
                            <button type="button"
                                class="btn btn-secondary border-radius-100 btn-block confirmation-btn"
                                data-dismiss="modal">
                                <i class="fa fa-times"></i>
                            </button>
                            Tidak
                        </div>
                        <div class="col-6">
                            <a href="/logout"
                                class="btn btn-primary border-radius-100 btn-block confirmation-btn text-light">
                                <i class="fa fa-check"></i>
                            </a>
                            Ya
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    {{-- Base Script Components --}}
    <script src="{{ asset('vendors/scripts/core.js') }}"></script>
    <script src="{{ asset('vendors/scripts/script.min.js') }}"></script>
    <script src="{{ asset('vendors/scripts/layout-settings.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/buttons.html5.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script src="{{ asset('src/plugins/sweetalert2/sweetalert2.all.js') }}"></script>
    <script src="{{ asset('src/plugins/apexcharts/apexcharts.min.js') }}"></script>

    <script>
        $('document').ready(function() {
            $('.data-table').DataTable({
                scrollCollapse: true,
                autoWidth: false,
                responsive: true,
                columnDefs: [{
                    targets: "datatable-nosort",
                    orderable: false,
                }],
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                "language": {
                    "info": "_START_-_END_ of _TOTAL_ entries",
                    searchPlaceholder: 'Cari apa saja Disini',
                    paginate: {
                        next: '<i class="ion-chevron-right"></i>',
                        previous: '<i class="ion-chevron-left"></i>'
                    }
                },
            });

        });

        function successAlert(message) {
            $('#sa-success', function() {
                swal({
                    title: 'Good job!',
                    text: `${message}`,
                    type: 'success',
                    confirmButtonClass: 'btn bg-success',
                });
            });
        }
    </script>

    @if ($title === 'Dashboard' || $title === 'Laporan Penjualan')
        <script>
            let year = new Date().getFullYear();
            $('document').ready(function() {
                getDataPenjualan(year);
            });
            let serverData = {};
            let options3 = {
                series: [],
                chart: {
                    type: 'bar',
                    height: 350,
                    toolbar: {
                        show: false,
                    },
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                    },
                },
                noData: {
                    text: 'Loading...'
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                xaxis: {
                    categories: []
                },
                fill: {
                    opacity: 1
                },
            };
            let chart = new ApexCharts(document.querySelector("#chart3"), options3);
            chart.render();

            function getDataPenjualan(year) {
                let getDataByYear = year;
                $.ajax({
                    url: '/apoteker/laporan/penjualan/get/' + getDataByYear,
                    method: 'GET',
                    success: function(response) {
                        serverData = ({
                            bulan: Object.keys(response.data),
                            data: Object.values(response.data),
                        });


                        let serverDataExtracted = ({
                            jumlah: {},
                            subtotal: {}
                        });

                        for (let i = 0; i < serverData.data.length; i++) {
                            const dataForMonth = serverData.data[i];
                            const month = serverData.bulan[i];

                            const subtotal = dataForMonth.reduce((acc, item) => acc + item.subtotal, 0);

                            serverDataExtracted.jumlah[month] = dataForMonth.length;
                            serverDataExtracted.subtotal[month] = subtotal;
                        }
                        console.log(serverDataExtracted);
                        chart.updateSeries([{
                            name: 'Penjualan',
                            data: Object.values(serverDataExtracted.jumlah)
                        }]);
                        chart.updateOptions({
                            xaxis: {
                                categories: Object.keys(serverDataExtracted.jumlah)
                            }
                        });

                    },
                    error: function(error, xhr) {
                        console.error(error);
                        console.log(xhr.responseText);
                    }
                });
            }
        </script>
    @endif


    @if ($title === 'Kasir')
        <script>
            filterKatalog('Semua');
            // refreshTable();
        </script>
    @endif

    @if ($title === 'Daftar Obat')
        <script>
            $(function() {
                getData('Semua');
            });
        </script>
    @endif

    @if (session()->has('success'))
        <script>
            const message = '{{ session('success') }}';
            successAlert(message);
        </script>
    @endif




    <script>
        $('document').ready(function() {
            var swiper = new Swiper(".mySwiper", {});
        });
    </script>



</body>

</html>
