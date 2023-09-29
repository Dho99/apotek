@extends('layouts.main')
@section('content')
    <div class="xs-pd-20-10 pd-ltr-20">
        <div class="title pb-20 d-flex">
            <h2 class="mb-0">{{ $title }}</h2>
            <button class="btn btn-sm btn-outline-success ml-auto" id="fastTransaction">
                <span class="font-weight-normal d-flex align-items-center">
                    <i class="icon-copy dw dw-flash font-weight-bold font-20 mr-2"></i>
                    Transaksi Cepat
                </span>
            </button>
        </div>

        <div class="row pb-10">
            <div class="col-xl-6 col-lg-6 col-md-12 mb-20 ">
                <div class="card-box height-100-p widget-style3">
                    <div class="d-flex flex-wrap">
                        <div class="widget-data">
                            <div class="font-20 text-secondary weight-500">
                                Diagram Penjualan
                            </div>
                        </div>
                        <div id="chart3" class="container"></div>
                        <a href="/apoteker/laporan/penjualan" class="text-dark mb-3 px-4">Lihat Selengkapnya</a>
                    </div>
                </div>

            </div>

            <div class="col-xl-6 col-lg-6 col-md-12 mb-20">
                <div class="card-box widget-style3" style="height: 70%;">
                    <div class="d-flex flex-wrap">
                        <div class="widget-data w-100">
                            <div class="font-20 text-secondary weight-500">
                                Data Stock Obat
                            </div>

                            @if (count($obat) === 0)
                                <div class="container-fluid my-3 rounded-lg">
                                    <div class="row bg-lightgreen">
                                        <div class="col-12">
                                            No Data
                                        </div>
                                    </div>
                                </div>
                            @else
                                {{-- @dd    ($obat) --}}
                                @foreach ($obat as $item)
                                    <div class="container-fluid my-3 rounded-lg">
                                        <div class="row bg-lightgreen text-center align-items-center py-2">
                                            <div class="col-lg-1">
                                                <div class="rounded-lg">
                                                    {{ $loop->iteration }}
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="rounded-lg">
                                                    {{ $item->updated_at->format('d-m-Y') }}
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="rounded-lg">
                                                    {{ $item->namaProduk }}
                                                </div>
                                            </div>

                                            <div class="col-lg-4 w-50 m-auto">
                                                @if ($item->stok <= 20 && $item->stok >= 10)
                                                    <div class="small bg-orange text-light rounded-lg p-1">
                                                        Stok Menipis
                                                    </div>
                                                @elseif ($item->stok <= 0 && $item->stok <= 10)
                                                    <div class="small bg-danger text-light rounded-lg p-1">
                                                        Stok Habis
                                                    </div>
                                                @else
                                                    <div class="small bg-success text-light rounded-lg p-1">
                                                        Stok Tersedia
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                            <a href="/apoteker/obat/list" class="px-3 mb-4 text-dark">Lihat Selengkapnya</a>
                        </div>

                    </div>
                </div>
                <div class="card-box widget-style3 my-3">
                    <div class="d-flex flex-wrap">
                        <div class="widget-data w-100">
                            <div class="font-20 text-secondary weight-500">
                                Saldo Kas
                                <div class="font-24 text-dark my-1 font-weight-bold">@currency($kas)</div>
                            </div>
                            <a href="/apoteker/laporan/keuangan">Lihat Selengkapnya</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row pb-10">
            <div class="col-xl-7 col-lg-8 col-md-12 col-sm-12 mb-20">
                <div class="card-box height-100-p widget-style3">
                    <div class="d-flex flex-wrap">
                        <div class="widget-data w-100">
                            <div class="font-20 text-secondary pb-3 weight-500">
                                Daftar Resep Masuk
                            </div>

                            {{-- @dd($data) --}}
                            @forelse ($dataProcessed as $item)
                                <div class="bg-lightgreen my-3 rounded-lg">
                                    <div class="row py-1 w-100 m-auto text-center align-items-center">
                                        <div class="col-xl-1 col-md-4 col-sm-6">
                                            <div class="rounded-lg py-2">
                                                {{ $loop->iteration }}
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-4 col-sm-6">
                                            <div class="rounded-lg px-0 py-0">
                                                {{ $item['created_at']->format('h:i d/m/y') }}
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-md-4 col-sm-6">
                                            <div class="rounded-lg py-2">
                                                {{ $item['pasien_id'] }}
                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-md-4 col-sm-6 text-center small align-items-center d-flex">
                                            @if ($item['isProceedByApoteker'] = 1)
                                                <div class="rounded-lg text-light bg-success p-2 m-auto">
                                                    Sudah Dibuat
                                                </div>
                                            @else
                                                <div class="rounded-lg text-light bg-danger p-2 m-auto">
                                                    Sudah Dibuat
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-xl-1 col-md-4 col-sm-6 ">
                                            <button class="border-0 mt-1 bg-transparent text-center text-danger font-24"
                                                data-toggle="modal"
                                                data-target="#daftar-resep-dashboard-modal-{{ $item['kode'] }}"
                                                type="button">
                                                <i class="icon-copy dw dw-edit"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade bs-example-modal-lg"
                                    id="daftar-resep-dashboard-modal-{{ $item['kode'] }}" tabindex="-1" role="dialog"
                                    aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                <div class="row border-bottom">
                                                    <div class="col-lg-6">
                                                        <p class="mb-1 font-18">Dr. {{ $item['dokter_id'] }}</p>
                                                        <p>{{ $item['dokter_id'] }}</p>
                                                    </div>
                                                    <div class="col-lg-6 text-right">
                                                        <p>{{ $item['created_at']->format('d:m:Y H:i') }}</p>
                                                    </div>
                                                </div>
                                                <div class="row my-2 border-bottom">
                                                    <div class="col-lg-6">
                                                        <p class="font-18">{{ $item['pasien_id'] }}</p>
                                                    </div>
                                                    <div class="col-lg-6 text-right">
                                                        <p>{{ $item['umur'] }}</p>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <p>{{ $item['gejala'] }}</p>
                                                    </div>
                                                </div>
                                                <div class="row d-flex font-weight-bold">
                                                    <div class="col-6">
                                                        Nama Produk
                                                    </div>
                                                    <div class="col-3">
                                                        Jumlah
                                                    </div>
                                                    <div class="col-3">
                                                        Satuan
                                                    </div>
                                                </div>
                                                @foreach ($item['namaProduk'] as $namaObatIndex => $namaObat)
                                                    <div class="row py-2 border-bottom">
                                                        <div class="col-6">
                                                            {{ $namaObat }} <br>
                                                            {{ $item['catatan'][$namaObatIndex] }}
                                                        </div>
                                                        <div class="col-3">
                                                            {{ $item['jumlah'][$namaObatIndex] }} <br>

                                                        </div>
                                                        <div class="col-3">
                                                            {{ $item['satuan'][$namaObatIndex] }}
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>

                                            <div class="modal-footer border-0 mb-3">
                                                <form action="/apoteker/resep/proses/{{ $item['kode'] }}" method="POST"
                                                    class="w-100 d-flex">
                                                    @csrf
                                                    @if ($item['isProceedByApoteker'] = '1')
                                                        <button type="button"
                                                            class="btn btn-secondary disabled w-75 m-auto"
                                                            data-dismiss="modal">
                                                            Resep Sudah Diproses
                                                        </button>
                                                    @endif
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                            @endforelse

                            @foreach ($dataNotProceed as $item)
                                <div class="bg-lightgreen my-3 rounded-lg">
                                    <div class="row py-1 w-100 m-auto text-center align-items-center">
                                        <div class="col-xl-1 col-md-4 col-sm-6">
                                            <div class="rounded-lg py-2">
                                                {{ count($dataProcessed) + $loop->iteration }}
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-4 col-sm-6">
                                            <div class="rounded-lg px-0 py-0">
                                                {{ $item->created_at->format('h:i d/m/y') }}
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-md-4 col-sm-6">
                                            <div class="rounded-lg py-2">
                                                {{ $item->pasien->nama }}
                                            </div>
                                        </div>

                                        <div
                                            class="col-xl-3 col-md-4 col-sm-6 text-center small align-items-center d-flex">
                                            <div class="rounded-lg text-light bg-danger p-2 m-auto">
                                                Belum dibuat
                                            </div>
                                        </div>
                                        <div class="col-xl-1 col-md-4 col-sm-6 ">
                                            <button class="border-0 mt-1 bg-transparent text-center text-danger font-24"
                                                onclick="if (confirm('Lanjut ke halaman proses resep?')) window.location.href='/apoteker/resep/list';">
                                                <i class="icon-copy dw dw-edit"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            {{-- @empty --}}





                            <a href="#" class="px-3 mb-4 text-dark sticky-bottom">Lihat Selengkapnya</a>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-xl-5 col-lg-4 col-md-12 col-sm-12 mb-20">
                <div class="card-box height-100-p widget-style3">
                    <div class="d-flex flex-wrap">
                        <div class="widget-data w-100">
                            <div class="font-20 pb-4 text-secondary weight-500">
                                Data Dokter
                            </div>

                            @if (count($isPresent) === 0)
                                <div class="container-fluid rounded-lg py-2">
                                    <div class="row text-center font-18">
                                        <div class="col-12">
                                            Belum Ada Dokter yang Hadir :(
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @foreach ($isPresent as $item)
                                <div class="container-fluid rounded-lg">
                                    <div class="row bg-lightgreen py-2 text-center align-items-center">
                                        <div class="col-xl-1 col-md-6">
                                            <div class=" rounded-lg text-center">
                                                {{ $loop->iteration }}
                                            </div>
                                        </div>
                                        <div class="col-xl-5 col-md-6">
                                            <div class="rounded-lg text-center">
                                                @php
                                                    $nama = explode(' ', $item->nama);
                                                    $namaDepan = array_shift($nama);
                                                @endphp
                                                Dr. {{ $namaDepan }}

                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-6">
                                            <div class=" rounded-lg text-center">
                                                {{ $item->kategoriDokter }}
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-6 w-50 m-auto">
                                            <div class="small bg-info text-light rounded-lg text-center p-1">
                                                Present
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach


                        </div>
                        {{-- <a href="#" class="px-3 mb-4 text-dark">Lihat Selengkapnya</a> --}}
                    </div>
                </div>
            </div>
        </div>

    </div>


    <div class="modal fade" id="fast-transaction-modal" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <div class="text-center font-20 font-weight-bold">
                        Transaksi Cepat Non Resep
                    </div>
                    @php
                        $pasiens = \App\Models\User::where('level', 3)->get();
                    @endphp
                    <div class="my-3">
                        <div class="mt-2 mb-3 text-left font-18 font-weight-bold w-75">
                            Kode Transaksi : <span id="trxCode"></span>
                            <div class="d-flex m-auto align-items-center w-100">
                                Nama Pasien : <span class="ml-1 font-weight-normal w-50">
                                    <select name="" class="form-control-sm" style="width: 100%;" id="pasienFastTrx">
                                        <option value=""></option>
                                        @foreach ($pasiens as $item)
                                            <option value="{{ $item->nama }}">{{ $item->nama }}</option>
                                        @endforeach
                                    </select>
                                </span>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center">
                            <input type="text" class="form-control rounded-right" style="width: 70%;"
                                id="codeProductInputField" placeholder="Masukkan Kode Obat">
                            <button class="btn btn-sm btn-success ml-auto rounded-right" onclick="addRowsToTable()">
                                <span>
                                    <i class="icon-copy dw dw-add font-20"></i>
                                </span>
                            </button>
                            <button class="btn btn-sm btn-danger ml-1 rounded-left" id="rowTableDeletor"
                                onclick="deleteTableRow()" disabled>
                                <span>
                                    <i class="icon-copy dw dw-delete-2"></i>
                                </span>
                            </button>
                        </div>
                        <div id="form-feedback" class="d-none text-danger text-left"></div>
                    </div>

                    <div class="table-responsive mb-3 " style="overflow-x: auto;">
                        <table id="table-transactions" class="table table-bordered">
                            <thead>
                                <tr>
                                    <td>No</td>
                                    <td>Kode Obat</td>
                                    <td>Nama Obat</td>
                                    <td>Jumlah</td>
                                    <td>Harga</td>
                                </tr>
                            </thead>
                            <tbody id="table-transactions-field">

                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="font-weight-bold text-right">Subtotal</td>
                                    <td id="subtotal-table-field"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="mb-3 d-flex">
                        <button class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button class="btn btn-success ml-auto" id="processTransactions" onclick="prosesPesanan()">Proses</button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        let number = 0;
        let total = 0;
        let kodeObat = [];
        let harga = [];
        let jumlahbarang = [];

        $().ready(function() {
            $('#pasienFastTrx').select2({
                tags: true,
                placeholder: 'Pilih atau tuliskan nama Pasien'
            });
            let kode = randomString() * 23313;
            $('#trxCode').text('TRX' + kode);

        });

        $('#fastTransaction').on('click', function() {
            $('#fast-transaction-modal').modal('show');

        });

        $('.form-control').on('keydown', function(e) {
            if (e.code === 'Enter') {
                addRowsToTable();
            }
        });

        function deleteTableRow() {
            let arrKode = kodeObat.length - 1;
            let kode = kodeObat[arrKode];
            if (number == 0) {
                $('#rowTableDeletor').attr('disabled', 'disabled');
            } else {
                jumlahbarang[`${kode}`] = 0;
                let hVal = harga[`${kode}`];
                total-=parseInt($(`#jumlah${kode}`).text()) * parseInt(hVal);
                kodeObat.pop();
                harga.pop();
                $('#subtotal-table-field').text(formatCurrency(parseInt(total)));
                $(`#row${kode}`).remove();
                number--;
            }
        };

        function addRowsToTable() {
            let inpf = $('.form-control');
            let formMsg = $('#form-feedback');
            let val = inpf.val();

            if (val.length === 0) {
                userMessage();
            } else {
                $('.table-responsive').prepend(
                    `<div class="loader m-auto position-absolute" style="left: 0; right: 0; text-align:center;"></div>`);
                setTimeout(() => {
                    $('.loader').remove();
                    $.ajax({
                        url: '/apoteker/obat/get/' + val,
                        method: 'GET',
                        success: function(response) {
                            let hasil = response.data[0];
                            if (hasil === undefined)
                            {
                                userMessage();
                                jumlah.val('');
                                $('#codeProductInputField').focus();
                            }
                            else
                            {
                                kodeHasCollected = kodeObat.includes(hasil.kode);
                                if (!kodeHasCollected) {
                                    kodeObat.push(hasil.kode);
                                    number++;
                                    $('#table-transactions-field').append(`
                                <tr id="row${hasil.kode}">
                                    <td>${number}</td>
                                    <td>${hasil.kode}</td>
                                    <td>${hasil.namaProduk}</td>
                                    <td style="max-width: 20px; padding: 0;"><input type="number" class=" w-100 p-2 border-0 text-center" placeholder="Jumlah" id="jumlah${hasil.kode}" value="1" oninput="calculateJumlah('${hasil.kode}')"></td>
                                    <td id="harga${hasil.kode}">${formatCurrency(hasil.harga)}</td>
                                </tr>
                                `);
                                    harga[`${hasil.kode}`] = hasil.harga;
                                    calculateJumlah(`${hasil.kode}`);
                                    $('#rowTableDeletor').removeAttr('disabled');
                                    jumlahbarang[`${hasil.kode}`] = 1;
                                } else {
                                    jumlahbarang[`${hasil.kode}`]+=parseInt(1);
                                    calculateJumlah(hasil.kode, 'tambah');
                                }

                                inpf.val('');
                                removeUserMessage();
                                $('#codeProductInputField').focus();
                                $('#subtotal-table-field').text(formatCurrency(parseInt(total)));
                                // console.log(Object.values(jumlahbarang));

                            }
                        },
                        error: function(response, error, xhr) {
                            errorAlert(response.responseText)
                            console.log(error.message);
                            console.log(xhr.responseText);
                        }
                    });
                }, 1500);

            }

            function userMessage() {
                inpf.addClass('form-control-danger');
                formMsg.removeClass('d-none').text('Masukkan kode barang dengan benar dan teliti');
            }

            function removeUserMessage() {
                inpf.removeClass('form-control-danger');
                formMsg.addClass('d-none').text('');
            }

        };

        function prosesPesanan() {
            if (kodeObat.length == 0) {
                errorAlert('Tidak dapat memproses Pesanan');
            }
            else if($('#pasienFastTrx').val() === ''){
                errorAlert('Masukkan Nama Pasien terlebih dahulu');
                $('#pasienFastTrx').focus();
            }
            else {
                let myForm = new FormData();
                myForm.append('kode', JSON.stringify(kodeObat));
                myForm.append('jumlah', JSON.stringify(Object.values(jumlahbarang)));
                myForm.append('total', total);
                myForm.append('kodePenjualan', $('#trxCode').text());
                myForm.append('dsc', 0);
                myForm.append('kategoriPenjualan', 'Non Resep');
                myForm.append('subtotal', total);
                myForm.append('pasienId', $('#pasienFastTrx').val());


                if(myForm){
                    ajaxUpdate('/resep/antrian/proses/', 'POST', myForm);
                }else{
                    errorAlert('Gagal memproses Transaksi');
                }

            }
        }

        function calculateJumlah(arg, act){
            let jumlah;
            if(act === undefined){
                jumlah = $(`#jumlah${arg}`).val();
            }else{
                jumlah = parseInt(jumlahbarang[arg]);
                console.log(jumlah);
            }

            $(`#jumlah${arg}`).val(jumlah);
            total += jumlah * parseInt(harga[arg]);
            $('#subtotal-table-field').text(formatCurrency(parseInt(total)));
        }


    </script>


@endsection
