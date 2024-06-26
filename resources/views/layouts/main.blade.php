<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>PharmaPal | {{ $title }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/core.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/icon-font.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/style.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('src/plugins/datatables/css/dataTables.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('src/plugins/sweetalert2/sweetalert2.min.css') }}" />
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" /> --}}
    <link rel="stylesheet" href="{{ asset('vendors/styles/pagination.css') }}">
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.0/dist/trix.css">

    @yield('plugins')
</head>

@yield('styles')

<body class="body bg-light h-auto position-relative">
    @include('partials.navbar')
    @include('partials.sidebar')
    @include('sweetalert::alert')

    {{-- @include('partials.notification-bar') --}}
    {{-- <div class="mobile-menu-overlay"></div> --}}


    <div class="main-container noprint">
        {{-- @dd(auth()->user()) --}}
        <div class="container pb-5 mt-3">
            <div class="title d-flex">
                <h2 class="mb-0">{{ $title }}</h2>
            </div>
            @yield('content')

        </div>
    </div>
    <footer class="position-fixed fixed-bottom bg-lightgreen footer d-inline-flex justify-content-end w-screen" style="z-index: 100;">
        <div class="py-3 px-5 d-inline-flex ">
                <h6 class="text-left">Aplikasi Klinik | Pharmapal</h6>

        </div>
    </footer>




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

    <script src="{{ asset('vendors/scripts/core.js') }}"></script>
    <script src="{{ asset('vendors/scripts/script.min.js') }}"></script>
    <script src="{{ asset('vendors/scripts/layout-settings.js') }}"></script>
    <script src="{{ asset('src/plugins/datatables/js/dataTables.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script src="{{ asset('src/plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('src/plugins/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('vendors/scripts/myScript/jquery.mask.min.js') }}"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.0/dist/trix.umd.min.js"></script> --}}

    <script>
        function initSel2Tags(arg) {
            $(arg).select2({
                tags: true,
                placeholder: 'Pilih opsi berikut atau tambahkan opsi baru'
            });
        }
        function printable(table, data, column) {
            $(table).empty();
            $(table).DataTable({
                data: data,
                responsive: true,
                searching: true,
                destroy: true,
                columns: column,
                autoWidth: false,
                columnDefs: [{
                    targets: "_all",
                    defaultContent: ""
                }],
                "language": {
                    paginate: {
                        next: '<i class="ion-chevron-right"></i>',
                        previous: '<i class="ion-chevron-left"></i>'
                    }
                },
                "lengthMenu": [
                    [25, 50, 100, 125, -1],
                    [25, 50, 100, 125, "All"]
                ],
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'colvis',
                        titleAttr: 'Kustomisasi Tampilan tabel untuk di export'
                    },
                    {
                        extend: 'copy',
                        text: 'Copy',
                        exportOptions: {
                            columns: ':visible',
                        }
                    }, {
                        extend: 'excel',
                        text: 'Excel',
                        exportOptions: {
                            columns: ':visible'
                        },
                    }, {
                        extend: 'pdf',
                        text: 'PDF',
                        exportOptions: {
                            columns: ':visible'
                        }
                    }, {
                        extend: 'print',
                        text: 'Print',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                ],
                error: function(error, xhr) {
                    console.error(error);
                    console.log(xhr.responseText);
                }
            }).draw();
        }

        function updateTable(table, data, column) {
            $(table).empty();
            $(table).DataTable({
                data: data,
                responsive: true,
                searching: true,
                destroy: true,
                columns: column,
                autoWidth: false,
                columnDefs: [{
                    targets: "_all",
                    defaultContent: ""
                }],
                "language": {
                    paginate: {
                        next: '<i class="ion-chevron-right"></i>',
                        previous: '<i class="ion-chevron-left"></i>'
                    }
                },
                "lengthMenu": [
                    [25, 50, 100, 125, -1],
                    [25, 50, 100, 125, "All"]
                ],
                error: function(error, xhr) {
                    console.error(error);
                    console.log(xhr.responseText);
                }
            });
        }

        function ajaxUpdate(url, method, dataForm) {
            $.ajax({
                url: url,
                method: method,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content")
                },
                processData: false,
                contentType: false,
                cache: false,
                data: dataForm,
                success: function(response) {
                    // console.log(response.data);
                    successAlert(response.message);
                    emptyModal();
                    refreshTable();

                },
                error: function(error, xhr) {
                    errorAlert(error.responseText);
                    console.error(error);
                    console.log(xhr.responseText);
                }
            });
        }

        function asyncAjaxUpdate(url, method, dataForm) {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: url,
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content")
                    },
                    cache: false,
                    data: dataForm,
                    success: function(response) {
                        resolve(response);
                    },
                    error: function(xhr) {
                        reject(xhr.responseText);
                    }
                });
            });
        }


        function formatCurrency(angka) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR'
            }).format(angka);
        }

        function inputMaskFormat(el) {
            $(el).mask('000,000,000', {
                reverse: true
            });
        }

        function changeToEdit(arg) {
            $('.btn.btn-info.ml-auto').addClass('d-none');
            $('#updateBtn').removeClass('d-none');
            $(`${arg} input`).removeAttr('readonly');
            $(`${arg} select`).removeAttr('disabled');
        }

        function randomString() {
            const randomNum = Math.floor(Math.random() * 9999);
            return randomNum;
        }

        function printInvoice() {
            let modal = $('modal-content');
            window.print(modal);
            emptyModal();
        }

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
                    [25, 50, 100, 125, -1],
                    [25, 50, 100, 125, "All"]
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
                Swal.fire({
                    title: 'Good job!',
                    text: `${message}`,
                    icon: 'success',
                    confirmButtonClass: 'btn bg-success',
                });
            });
        }

        function errorAlert(message) {
            $('#sa-error', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: `${message}`,
                    confirmButtonClass: 'btn bg-danger',
                });
            });
        }

        function deleteData(url, arg) {
            $.ajax({
                url: url + arg,
                method: 'GET',
                success: function(response) {
                    successAlert(response.message);
                    refreshTable();
                },
                error: function(error, xhr) {
                    console.log(error);
                    console.log(xhr.responseText);
                },
            });
        }

        function decisionAlert(title, message, cancelMessage){
            return new Promise((resolve, reject) => {
                Swal.fire({
                    title: title,
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it !',
                }).then((result) => {
                    if(result.isConfirmed){
                        resolve(true);
                    }else if(result.isDismissed){
                        reject(cancelMessage);
                    }
                });
            });
        }

    </script>
    @stack('scripts')
    @if (auth()->user()->level == 'Apoteker')
        @if ($title === 'Dashboard' || $title === 'Laporan Penjualan')
            <script>
                let year = new Date().getFullYear();
                $().ready(function() {
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
                        text: 'Tidak ada record Data 🤡'
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
                        categories: [],
                    },
                    fill: {
                        opacity: 1
                    },
                    tooltip: {
                        y: {},
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

                                const subtotal = dataForMonth.reduce((acc, item) => acc + parseInt(item.subtotal), 0);

                                serverDataExtracted.jumlah[month] = dataForMonth.length;
                                serverDataExtracted.subtotal[month] = subtotal;
                            }

                            chart.updateSeries([{
                                data: Object.values(serverDataExtracted.jumlah),
                            }, ]);
                            chart.updateOptions({
                                xaxis: {
                                    categories: Object.keys(serverDataExtracted.jumlah)
                                },
                                tooltip: {
                                    y: {
                                        formatter: function(value, {
                                            series,
                                            seriesIndex,
                                            dataPointIndex,
                                            w
                                        }) {
                                            // Tambahkan data tambahan dari serverDataExtracted.subtotal ke tooltip
                                            return "Jumlah: " + value + "<br>" + "Total: " + formatCurrency(
                                                serverDataExtracted.subtotal[w.config.xaxis.categories[
                                                    dataPointIndex]]);
                                        },
                                        title: {
                                            formatter: function() {
                                                return ''
                                            },
                                        },
                                    },

                                },
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
    @endif

</body>

</html>
