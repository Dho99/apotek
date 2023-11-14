@extends('layouts.main')
{{-- @section('content') --}}
    <div class="xs-pd-20-10 pd-ltr-20 float-right container pl-5">
        <div class="title pb-20">
            <h2 class="h3 mb-0">{{ $title }}</h2>
        </div>

        {{-- @dd($datas) --}}
        <div class="card-box mb-30">
            <div class="pd-20">
                <div class="row d-flex mb-3">
                    <div class="col-lg-8">
                        <div class="font-weight-bold font-20">Diagram Penjualan</div>
                    </div>
                    <div class="col-lg-4">
                        <select name="" id="yoy" style="width: 100%;" class="custom-select2 form-control"
                            onchange="getDataByYearFromSelect()">
                            <option value="">Select Year</option>
                            @foreach ($yearOption as $key => $item)
                                <option value="{{ $key }}">{{ $key }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div id="chart3" class="p-1 border"></div>
            </div>
        </div>

        <div class="card-box mb-30">
            <div class="pd-20">
                <div class="font-weight-bold font-20">Tabel Penjualan</div>
                        <div class="row mt-3">
                            <div class="col-lg-8 font-20">
                                Urutkan Per <span class="mr-2" id="mo"></span> <span id="yrs"></span>
                            </div>

                            <div class="col-lg-4 d-flex">
                                <select name="" id="orByMo" class="form-control mx-1 noprint" onchange="refreshTable()">
                                    <option value="">Bulan</option>
                                    @foreach ($perBulan as $key => $item)
                                        <option value="{{$key}}">{{$key}}</option>
                                    @endforeach
                                </select>


                                <select name="" id="orByYear" class="form-control mx-1 noprint" onchange="refreshTable()">
                                    <option value="">Tahun</option>
                                    @foreach ($yearOption as $key => $item)
                                        <option value="{{$key}}">{{$key}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- <div class="container"> --}}
                            <button class="btn btn-sm btn-secondary noprint" onclick="window.print();">
                                Print
                            </button>
                        {{-- </div> --}}
                </div>
                <div class="pb-20">
                    <table class="data-table table nowrap" id="myPenjualanTable">
                        <thead>
                            <tr>
                                <th class="table-plus">No</th>
                                <th>Tanggal</th>
                                <th>Nama Pasien</th>
                                <th>Kode Transaksi</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <div class="container-fluid">
                        <p>Total Data : <span id="countData"></span></p>
                        <p>Total Nominal : <span id="countNominal"></span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
{{-- @endsection --}}
<div class="modal fade" id="invoiceModal" role="dialog" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow">
            <div class="modal-body">
                <div class="text-center">
                    <img src="{{ asset('src/images/logo-pharmapal.png') }}" width="200px" height="100px"
                        alt="">
                </div>
                <h5 class="text-center modal-title"></h5>
                <div class="dropdown-divider"></div>
                <div class="row">
                    <div class="col-12">
                        <div>Tanggal Proses : <span id="createdAt" class="font-weight-bold"></span></div>
                        <div>Nama Dokter : <span id="namaDokter" class="font-weight-bold"></span></div>
                        <div>Nama Apoteker : <span id="namaApoteker" class="font-weight-bold"></span></div>
                        <div>Nama Pasien : <span id="namaPasien" class="font-weight-bold"></span></div>
                    </div>
                </div>
                <div id="table-invoice-wrapper" class="my-4">

                </div>
                {{-- <div class="text-center mb-4">
                    <h5>Semoga lekas sembuh </h5>
                </div> --}}
                <button class="btn btn-secondary noprint" onclick="emptyModal()">Tutup</button>
                <button class="btn btn-success float-right noprint" onclick="printInvoice()"> <span
                        class="icon-copy dw dw-print"></span> Print</button>
            </div>
        </div>
    </div>
</div>
<script>

    function emptyModal() {
        $('#table-invoice-wrapper').empty();
        $('#invoiceModal').modal('hide');
    }

    function modalShow(kode, kategori, namaApoteker, namaDokter, namaPasien, namaProduk, subtotal, kategori, harga,
        deskripsi, jumlah, created_at, isSuccess) {

        const table = $('<table class="table table-bordered"></table>');
        const thead = $('<thead><tr><th>No</th><th>Nama Produk</th><th>Jumlah</th><th>Harga</th></tr></thead>');
        const tbody = $('<tbody></tbody>');

        // Loop melalui array nomorProduk dan tambahkan nomor ke dalam tabel

        for (let i = 0; i < namaProduk.length; i++) {

            const row1 = $('<tr></tr>');


            const cell1 = $('<td></td>').text(i + 1);
            row1.append(cell1);


            const cell2 = $('<td></td>').text(namaProduk[i]);
            row1.append(cell2);



            const cell3 = $('<td></td>').text(jumlah[i]);
            row1.append(cell3);


            const cell4 = $('<td></td>').text(formatCurrency(harga[i]));
            row1.append(cell4);



            tbody.append(row1);

        }
        const row2 = $('<tr></tr>');
        const cell4 = $('<td colspan="3"><div class="font-weight-bold font-18 text-right">Total</div></td>');
        const cell5 = $('<td></td>').text(formatCurrency(subtotal));
        row2.append(cell4, cell5);
        tbody.append(row2);

        table.append(thead);
        table.append(tbody);



        $('.modal-title').text('Invoice Transaksi ' + kode);
        $('#createdAt').text(created_at);
        if (`${namaDokter}` !== '') {
            $('#namaDokter').text(`${namaDokter}`);
        } else {
            $('#namaDokter').text('-');
        }

        if (`${namaPasien}` !== '') {
            $('#namaPasien').text(`${namaPasien}`);
        } else {
            $('#namaPasien').text('-');
        }

        if (`${namaApoteker}` !== '') {
            $('#namaApoteker').text(`${namaApoteker}`);
        } else {
            $('#namaApoteker').text('-');
        }

        $('#total').text(subtotal);
        $('#table-invoice-wrapper').append(table);


        $('#invoiceModal').modal('show');
    }

    function getDataByYearFromSelect() {
        let value = $('#yoy').val();
        let year = new Date().getFullYear();
        if (value === '') {
            getDataPenjualan(year);
        } else {
            getDataPenjualan(value);
        }
        // console.log(value);
    }

    function invoiceModal(kode) {
        $.ajax({
            url: '/apoteker/laporan/penjualan/inovice/' + kode,
            method: 'GET',
            success: function(response) {
                const hasil = response.data[0];
                modalShow(hasil.kode, hasil.kategori, hasil.namaApoteker, hasil.namaDokter, hasil
                    .namaPasien, hasil.namaProduk, hasil.subtotal, hasil.kategori, hasil.harga, hasil
                    .deskripsi, hasil.jumlah, hasil.created_at, hasil.isSuccess);
            },
            error: function(error, xhr) {
                console.error(error);
                console.log(xhr.responseText);
            }
        });
    }

    function refreshTable(){
        let bulan = $('#orByMo').val();
        let tahun = $('#orByYear').val();
        console.log(bulan, tahun);
        $.ajax({
            method: 'GET',
            url: '/apoteker/laporan/penjualan/get',
            data: {
                month: bulan,
                year: tahun
            },
            success: function(response){
                $('#countData').text(response.data.length);
                let nomArr = response.data;
                let nom = [];
                nomArr.map((item) => {
                    nom.push(item.subtotal);
                });
                let totalSubtotal = nom.reduce((accumulator, currentValue) => accumulator + currentValue, 0);
                $('#countNominal').text(formatCurrency(totalSubtotal));
                $('#myPenjualanTable').DataTable({
                    data: response.data,
                    dom: 'Bfrtip',
                    buttons: [{
                        extend: 'colvis',
                        titleAttr: 'Kustomisasi Tampilan tabel untuk di export'
                    }],
                    responsive: true,
                    searching: true,
                    destroy: true,
                    columns: [
                        {
                            render: function(data, type, row, meta){
                                return meta.row + meta.settings._iDisplayStart + 1
                            }
                        },
                        {
                            render: function(data, type, row){
                                return moment(`${row.created_at}`).format(
                                    'DD/MM/YYYY'
                                );
                            }
                        },
                        {
                            data: 'pasien.nama'
                        },
                        {
                            data: 'kodePenjualan'
                        },
                        {
                            render: function(data, type, row){
                                return formatCurrency(`${row.subtotal}`);
                            }
                        },
                        {
                            render: function(data, type, row){
                                return `
                                <div class="dropdown">
                                    <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                                        href="#" role="button" data-toggle="dropdown">
                                        <i class="dw dw-more"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                        <a class="dropdown-item"
                                            onclick="invoiceModal('${row.kodePenjualan}')">
                                            <i class="dw dw-invoice"></i> Invoice
                                        </a>
                                    </div>
                                </div>
                                `;

                            }
                        }
                    ],
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
            },
            error: function(error, xhr){
                console.log(error.message);
                // console.log(xhr.responseText);
                errorAlert(xhr.responseText);
            }
        });
    }
</script>

