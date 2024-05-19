@extends('layouts.main')
@section('content')
    <div class="">
        <div class="title d-flex">
            <h2 class="mb-0">{{ $title }}</h2>
            <button class="btn btn-sm btn-outline-success ml-auto shadow px-2" id="fastTransaction">
                <span class="font-weight-normal d-flex align-items-center">
                    <i class="icon-copy dw dw-flash font-weight-bold font-20 mr-2"></i>
                    Transaksi Cepat
                </span>
            </button>
        </div>
        {{-- @dd($dataTerkirim) --}}
        <div class="row my-3">
            <div class="col-lg-4 col-md-6 col-12 mb-20">
                <div class="bg-white shadow rounded-lg">
                    <div class="row pt-2 py-3 px-4">
                        <div class="col-12 d-flex">
                            <h6 class="d-flex align-items-center">Jumlah Pasien</h6>
                            <div class="badge bg-lightgreen ml-auto">2059</div>
                        </div>
                        <div class="col-12 my-2">
                            <h4>{{$countPatients}} <span class="ml-3">Pasien</span></h4>
                        </div>
                        <div class="col-12 my-2">
                            <a href="/" class="text-success text-decoration-none weight-700">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-12 mb-20">
                <div class="bg-white shadow rounded-lg">
                    <div class="row pt-2 py-3 px-4">
                        <div class="col-12 d-flex">
                            <h6 class="d-flex align-items-center">Jumlah Dokter</h6>
                            <div class="badge bg-lightgreen ml-auto">2059</div>
                        </div>
                        <div class="col-12 my-2">
                            <h4>{{$countDokter}} <span class="ml-3">Pasien</span></h4>
                        </div>
                        <div class="col-12 my-2">
                            <a href="/" class="text-success text-decoration-none weight-700">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-12 mb-20">
                <div class="bg-white shadow rounded-lg">
                    <div class="row pt-2 py-3 px-4">
                        <div class="col-12 d-flex">
                            <h6 class="d-flex align-items-center">Jumlah Kunjungan</h6>
                            <div class="badge bg-lightgreen ml-auto">2059</div>
                        </div>
                        <div class="col-12 my-2">
                            <h4>{{$countKunjungan}} <span class="ml-3">Pasien</span></h4>
                        </div>
                        <div class="col-12 my-2">
                            <a href="/" class="text-success text-decoration-none weight-700">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="bg-white shadow rounded-lg">
                    <div class="row pt-2 py-3 px-4">
                        <div class="col-12 d-flex">
                            <h6 class="d-flex align-items-center">Jumlah Kunjungan</h6>
                            <div class="badge bg-lightgreen ml-auto">2059</div>
                        </div>
                        <div class="col-12 my-2">
                            <h4>{{$countKunjungan}} <span class="ml-3">Pasien</span></h4>
                        </div>
                        <div class="col-12 my-2">
                            <a href="/" class="text-success text-decoration-none weight-700">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <script>
        let jumlah = 0;
        let number = 0;
        let total = 0;
        let kodeObat = [];
        let harga = [];
        let jumlahbarang = [];
        let stokObat = [];

        $().ready(function() {
            $('#pasienFastTrx').select2({
                tags: true,
                placeholder: 'Pilih atau tuliskan nama Pasien'
            });
            randomTrxCode();
            countProduct();
            countKas();
        });

        function randomTrxCode(){
            let kode = randomString() * 23313;
            $('#trxCode').text('TRX' + kode);
        }

        function countProduct(){
            $('#countProduct').empty();
            $.ajax({
                url: '/apoteker/count/produk/dashboard',
                method: 'GET',
                success: function(response){
                    const hasil = response.obat;
                    hasil.forEach(item => {
                        $('#countProduct').append(`
                        <div class="row bg-lightgreen text-center align-items-center py-2 my-2">
                            <div class="col-lg-3">
                                <div class="rounded-lg">
                                    ${item.kode}
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="rounded-lg">
                                    ${item.namaProduk}
                                </div>
                            </div>

                            <div class="col-lg-4 w-50 m-auto">
                                ${item.stok >= 10 && item.stok < 20 ? '<div class="small bg-warning text-light rounded-lg p-1">Stok Menipis</div>' : item.stok >= 1 && item.stok < 10 ? '<div class="small bg-orange text-light rounded-lg p-1">Stok Kritis</div>' : item.stok <= 1 ? '<div class="small bg-danger text-light rounded-lg p-1">Stok Habis</div>' : ' <div class="small bg-success text-light rounded-lg p-1">Stok Tersedia</div>'}
                            </div>
                        `);
                    });
                },
                error: function(error, xhr){
                    errorAlert(xhr.responseText);
                    console.log(error.message);
                }
            });
        }

        function countKas(){
            $.ajax({
                url: '/apoteker/count/kas/dashboard',
                method: 'GET',
                success: function(response){
                    const data = response.kas;
                    $('#kas').text(formatCurrency(data));
                },
                error: function(error, xhr){
                    errorAlert(xhr.responseText);
                    console.log(error.message);
                }
            });
        }

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
                            // console.log(response.data);
                            let hasil = response.data[0];
                            if (hasil === undefined)
                            {
                                userMessage();
                                val.val('');
                                $('#codeProductInputField').focus();
                            }
                            else if (hasil.stok < 1){
                                userMessage('Stok anda tidak mencukupi');
                                val.val('');
                                $('#codeProductInputField').focus();
                            }
                            else
                            {
                                kodeHasCollected = kodeObat.includes(hasil.kode);
                                stokObat[hasil.kode] = hasil.stok;
                                if (!kodeHasCollected) {
                                    kodeObat.push(hasil.kode);
                                    number++;
                                    $('#table-transactions-field').append(`
                                <tr id="row${hasil.kode}">
                                    <td>${number}</td>
                                    <td id="kode${hasil.kode}">${hasil.kode}</td>
                                    <td>${hasil.namaProduk}</td>
                                    <td style="max-width: 20px; padding: 0;"><input type="number" class=" w-100 p-2 border-0 text-center" placeholder="Jumlah" value="1" id="jumlah${hasil.kode}" oninput="calculateJumlah('${hasil.kode}','incbyinput')"></td>
                                    <td id="harga${hasil.kode}">${formatCurrency(hasil.harga)}</td>
                                </tr>
                                `);
                                    jumlahbarang[hasil.kode]=0;
                                    harga[`${hasil.kode}`] = hasil.harga;
                                    $('#rowTableDeletor').removeAttr('disabled');
                                }
                                else{
                                    errorAlert('Kode sudah ada');
                                }


                                inpf.val('');
                                calculateJumlah(`${hasil.kode}` ,'incbyinput');
                                removeUserMessage();
                                $('#codeProductInputField').focus();

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

            function userMessage(arg) {
                let msg;
                if(arg != undefined){
                    msg = arg;
                }else{
                    msg = 'Masukkan kode barang dengan benar dan teliti';
                }
                inpf.addClass('form-control-danger');
                formMsg.removeClass('d-none').text(msg);
            }

            function removeUserMessage() {
                inpf.removeClass('form-control-danger');
                formMsg.addClass('d-none').text('');
            }

        };

        function calculateJumlah(arg, act) {
            let jumlahVal = $(`#jumlah${arg}`).val();
            if(jumlahVal > stokObat[arg]){
                errorAlert('Stok produk tidak mecukupi');
                $(`#jumlah${arg}`).val('1');
            }else{
                if (act === 'incbyinput') {
                    total -= jumlahbarang[arg] * harga[arg];
                    if (jumlahVal < 0) {
                        errorAlert('Tidak bisa memasukkan angka kurang dari 0');
                    } else {
                        jumlahbarang[arg] = jumlahVal;
                        total += jumlahbarang[arg] * harga[arg];
                        $('#subtotal-table-field').text(formatCurrency(total));
                    }
                }
            }
        }

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
                    ajaxUpdate('/resep/antrian/proses', 'POST', myForm);
                    let year = new Date().getFullYear();
                    getDataPenjualan(year);
                    countProduct();
                    countKas();
                }else{
                    errorAlert('Gagal memproses Transaksi');
                }
            }
        }

        function emptyModal(){
            if(confirm('Apakah anda ingin mencetak Invoice ?') ? printInvoice() : clearModal());
        }

        function clearModal(){
            jumlah = 0;
            number = 0;
            total = 0;
            kodeObat = [];
            harga = [];
            jumlahbarang = [];
            $('#table-transactions-field').empty();
            randomTrxCode();
            $('#pasienFastTrx').val('').change();
            $('#subtotal-table-field').text('');
            $('#fast-transaction-modal').modal('hide');
        }


    </script>


@endsection
<div class="modal fade" id="fast-transaction-modal" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content shadow">
            <div class="modal-body text-center">
                 <div class="text-center">
                    <img src="{{ asset('src/images/logo-pharmapal.png') }}" width="200px" height="100px"
                        alt="">
                </div>
                <div class="text-center font-20 font-weight-bold">
                    Transaksi Cepat Non Resep
                </div>
                @php
                    $pasiens = \App\Models\User::where('roleId', 3)->get();
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
                        <input type="text" class="form-control rounded-right noprint" style="width: 70%;"
                            id="codeProductInputField" placeholder="Masukkan Kode Obat">
                        <button class="btn btn-sm btn-success ml-auto rounded-right noprint" onclick="addRowsToTable()">
                            <span>
                                <i class="icon-copy dw dw-add font-20"></i>
                            </span>
                        </button>
                        <button class="btn btn-sm btn-danger ml-1 rounded-left noprint" id="rowTableDeletor"
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
                    <button class="btn btn-secondary noprint" data-dismiss="modal">Batal</button>
                    <button class="btn btn-success ml-auto noprint" id="processTransactions" onclick="prosesPesanan()">Proses</button>
                </div>

            </div>
        </div>
    </div>
</div>
