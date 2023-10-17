@extends('layouts.main')
<style>
    #togglerbar:hover {
        background-color: #50D890 !important;
        color: #fff;
    }
</style>
@section('content')
    <div class="xs-pd-20-10 pd-ltr-20">
        <div class="checkout-bar d-none">

            <div id="pasientopwrapper">

            </div>
            <div class="bg-lightgreen py-3 px-2 border-bottom checkout-bar-top align-items-center d-flex">
                <button class="font-weight-bold btn btn-sm btn-outline-secondary " onclick="closeCheckout()">
                    Tutup
                </button>
                <span class="font-weight-bold ml-auto" id="trxCode"></span>
            </div>
            <div id="obatCart" class="px-3">


            </div>
            <div class="border-top py-2 border-success bg-lightgreen">
                <div class="mt-3 mb-4 px-3">
                    <div class="row">
                        <div class="col-6 text-left">
                            SubTotal
                        </div>
                        <div class="col-6 text-right">
                            <div id="subtotal">0</div>
                        </div>
                    </div>
                    <div class="dropdown-divider"></div>
                    <div class="row d-flex align-items-center d-flex">
                        <div class="col-6 text-left">
                            Discount
                        </div>
                        <div class="col-6 d-flex align-items-center">
                            <input type="text" class="form-control w-50 ml-auto" placeholder="Discount" id="dsc"
                                oninput="calculateGrandTotal()">
                            <span>%</span>
                        </div>
                    </div>
                    <div class="dropdown-divider"></div>
                    <div class="row font-weight-bold">
                        <div class="col-6 text-left">
                            Grand Total
                        </div>
                        <div class="col-6 text-right">
                            <div id="grandTotal"></div>
                        </div>
                    </div>
                    <div class="dropdown-divider"></div>
                    <div class="row d-flex mt-4">
                        <button class="btn btn-success w-75 m-auto" onclick="prosesPesanan()">Selesaikan Pesanan</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="title pb-20">
            <h2 class="h3 mb-0 d-flex">{{ $title }}
                <button class="btn ml-auto bg-lightgreen shadow-sm text-green position-sticky" id="togglerbar"
                    onclick="openCheckoutBar()"><i class="icon-copy dw dw-open-book font-30 font-weight-bold"></i></button>
            </h2>
            <p class="font-weight-bold font-20 mt-2">Antrian Resep</p>
        </div>

        <div class="container-fluid px-0 h-25">
            <div class="swiper mySwiper">
                <div class="swiper-wrapper">

                </div>
            </div>
        </div>

        <h5 class="h4 mb-20 d-flex">Katalog Obat
            <span class="ml-auto">
                <input type="text" name="" id="searchProduct" placeholder="Search Item"
                class="form-control">
            </span>
        </h5>


        <div class="tab my-2">
            <ul class="nav nav-tabs customtab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" onclick="filterKatalog('Semua')" role="tab"
                        aria-selected="true">Semua</a>
                </li>
                @foreach ($satuans as $item)
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" role="tab" onclick="filterKatalog('{{$item->satuan}}')"
                        aria-selected="false">{{$item->satuan}}</a>
                    </li>
                @endforeach
            </ul>



            <div class="tab-pane fade show active mt-2" id="contact2" role="tabpanel">
                <div class="row clearfix" id="katalogCard">
                </div>
            </div>


        </div>


        <div class="modal fade" id="nama-pasien-modal" data-backdrop="static" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body font-18 my-2">
                        <label for="" class="d-flex">Pilih atau Masukkan Nama Pasien
                            <span class="ml-auto"><i class="icon-copy bi bi-info-circle float-right font-20 mb-2" data-toggle="tooltip"
                                title="Tambah pasien baru dengan mengetikkan nama Pasien baru, Tambahkan spasi untuk mengubah data yang tersedia"></i></span>
                        </label>
                        <select name="" class="form-control-lg" style="width: 100%;" id="pasienSelect" onchange="savePasien()" required>
                            <option value=""></option>
                            @foreach ($pasiens as $item)
                                <option value="{{$item->nama}}">{{$item->nama}}</option>
                            @endforeach
                        </select>
                        <div class="my-3 d-flex">
                            <button class="btn btn-outline-success d-none ml-auto" id="savePasien" onclick="">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="description-modal" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content container">
                    <div class="my-2 text-center font-18 font-weight-bold">
                        Deskripsi Produk
                    </div>
                    <div class="modal-body font-18">
                    </div>
                        <button type="button"
                            class="btn btn-secondary my-3 w-50 mx-auto my-3"
                            onclick="dismissModal()">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>


        <script>

        let cartItems = {};
        let randCodeCollector;
        let proccessed = 0;
        let isnonresep = 0;
        let pasienId;
        let dokterId = 0;
        let kodeResep;
        let kategoriPenjualan;

        $().ready(function() {
            filterKatalog('Semua');
            // refreshTable();
            let swiper = new Swiper(".mySwiper", {});
            initSel2();
        });

        function initSel2(){
            $('#pasienSelect').select2({
                tags: true
            });
        }

        function savePasien(){
            let sVal = $('#pasienSelect').val();
            if(sVal === ''){
                $('#savePasien').addClass('d-none');
            }else{
                $('#savePasien').removeClass('d-none');
            }
        };

        $('#savePasien').on('click', function(){
            let value = $('#pasienSelect').val();
            $('#pasientopwrapper').append(`
                <div class="row container" style="margin-top: 80px; margin-bottom: -40px;">
                    <div class="col-12">
                        <div>Nama Pasien : <span class="font-weight-bold" id="namaPasien">${value}</span></div>
                    </div>
                </div>
            `);
            $('#nama-pasien-modal').modal('hide');
            initSel2();
            pasienId = value;
        });


        $('#searchProduct').on('input', function() {
            let val = $(this).val();
            if (val) {
                $('.col-lg-3.col-md-6.col-sm-12.mb-30').each(function() {
                    let text = $(this).text().toLowerCase();
                    if (text.includes(val.toLowerCase())) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            } else {
                $('.col-lg-3.col-md-6.col-sm-12.mb-30').show();
            }
        });


        function openCheckoutBar() {
            $('.checkout-bar').removeClass('d-none');

        }

        function filterKatalog(satuan) {
            const hasil = `${satuan}`;
            $.ajax({
                url: '/katalog/filter/' + hasil,
                method: 'GET',
                success: function(response) {
                    const hasil = response.data;
                    // console.log(hasil);
                    if (hasil.length == 0) {
                        $('#katalogCard').empty().append(`
                            <div class="col-lg-12 col-md-12 col-sm-12 mb-30">
                                <div class="alert alert-warning w-100" role="alert">
                                    Data Katalog ${satuan} tidak Ditemukan
                                </div>
                            </div>
                        `);
                    } else {
                        $('#katalogCard').empty();
                        hasil.forEach(produk => {
                            const kode = produk.kode;
                            const image = produk.image;
                            const namaProduk = produk.namaProduk;
                            const harga = produk.harga;
                            const stok = produk.stok;
                            const flagsUrl = '{{ URL::asset('/storage/') }}' + `/${image}`;

                            $('#katalogCard').append(`
                                <div class="col-lg-3 col-md-4 col-sm-4 mb-30" onclick="checkout('${kode}', '${image}', '${namaProduk}', '${harga}', '${stok}')">
                                    <div class="card card-box p-0 shadow">
                                        <img class="card-img-top img-fluid w-100" style="height: 210px;"
                                            src="${flagsUrl}" alt="Card image cap" />
                                        <div class="card-body bg-lightgreen-sidebar text-dark">
                                            <span class="text-dark font-weight-bold">${kode}</span>
                                            <h5 class="card-title weight-500">${namaProduk}
                                                <span class="float-right" id="stokProduk${kode}">
                                                    ${stok}
                                                </span>
                                            </h5>
                                            <p class="card-text text-dark d-flex">
                                                <span class="">${formatCurrency(harga)}</span>
                                                <span class="ml-auto">${produk.satuan}</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            `);
                        });
                    }
                },
                error: function(xhr, textStatus, errorThrown) {
                    console.log(xhr.responseText);
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            });
        }

        function prosesResep(kode) {
            let collecteditems = {};
            // console.log(isnonresep);
            if (proccessed == 0 && isnonresep == 0) {
                $.ajax({
                    url: '/apoteker/resep/proses/transaksi/' + kode,
                    method: 'GET',
                    success: function(response) {
                        $('#pasientopwrapper').empty();
                        cartItems[`${kode}`] = 1;
                        const hasil = response.data[0];
                        pasienId = hasil.namaPasien;
                        dokterId = hasil.namaDokter;
                        kategoriPenjualan = 'Resep';
                        kodeResep = hasil.kode;
                        $('#pasientopwrapper').append(`
                        <div class="row container" style="margin-top: 80px; margin-bottom: -40px;">
                            <div class="col-12">
                                <div>Nama Pasien : <span class="font-weight-bold" id="namaPasien">${hasil.namaPasien}</span></div>
                                <div>Diproses Pada : <span class="font-weight-bold">${hasil.created_at}</span></div>
                                <div>Dokter Resep : <span class="font-weight-bold" id="namaDokter">${hasil.namaDokter}</span></div>
                                <div>Kode Resep : <span class="font-weight-bold" id="kodeResep">${hasil.kode}</span></div>
                                ${hasil.catatanDokter != undefined || null ? `<div>Catatan Dokter : <span class="font-weight-bold" id="kodeResep">${hasil.catatanDokter}</span></div>` : ''}
                            </div>
                        </div>
                    `);

                        const datas = {
                            kodeProduk: hasil.kodeProduk,
                            imageProduk: hasil.image,
                            namaProduk: hasil.namaProduk,
                            hargaProduk: hasil.harga,
                            stokProduk: hasil.stok,
                            catatanProduk: hasil.catatan,
                        }

                        for (let i = 0; i < datas.kodeProduk.length; i++) {
                            let kode = datas.kodeProduk[i];
                            let image = datas.imageProduk[i];
                            let name = datas.namaProduk[i];
                            let price = datas.hargaProduk[i];
                            let stock = datas.stokProduk[i];
                            let catatan = datas.catatanProduk[i];

                            checkout(kode, image, name, price, stock, catatan);
                            proccessed = 1;

                        }

                        $(`#daftarResep${kode}`).hide();

                    },
                    error: function(response, error, xhr) {
                        errorAlert(response.responseText)
                        console.log(error.message);
                        console.log(xhr.responseText);
                    }

                });

            } else {
                errorAlert('Tidak bisa memproses dua resep Sekaligus');
            }
        }

        function checkout(kode, image, name, price, stock, catatan) {
            const stokString = parseInt($(`#stokProduk${kode}`).text());
            if (proccessed != 1) {
                isnonresep = 1;
                // console.log(isnonresep);
                if (stokString > 0) {
                    $('.checkout-bar').removeClass('d-none');
                    if (randCodeCollector === undefined) {
                        randCodeCollector = (parseInt(Math.floor(Math.random() * 99999)));
                        $('#trxCode').text('TRX' + randCodeCollector);
                    } else {
                        $('#trxCode').text('TRX' + randCodeCollector);
                    }
                    let cartKode = kode.toString();
                    if (cartItems[cartKode] !== undefined) {
                        counter('tambah', cartKode, price, stock);
                    } else {
                        cartItems[cartKode] = 1;
                        const imageUrl = '{{ URL::asset('/storage/') }}' + `/${image}`;
                        $('.checkout-bar').removeClass('d-none');
                        $('#obatCart').append(`
                    <div class="container rounded small shadow p-3 my-2" id="list${cartKode}">
                        <div class="row">
                            <div class="col-lg-4">
                                <img src="${imageUrl}" alt="test" class="img-fluid">
                            </div>
                            <div class="col-lg-5">
                                <span class="font-weight-bold font-20">
                                    ${name}
                                </span>
                                <br>
                                <span>
                                    <a href="#" onclick="showDescription('${kode}')">Detail / Deskripsi</a>
                                </span>

                                    ${catatan != undefined || null ? `<div class="my-2">${catatan}</div>` : ''}

                                <div class="counter-bar font-20 pt-2 d-flex m-auto text-green align-items-center">
                                    ${ proccessed == 1 ? `

                                        <span class="mx-3 text-dark" id="counted${kode}">0</span><span class="text-dark">Buah</span>

                                        `: ` <button class="rounded-circle bg-transparent border border-success px-2"
                                            onclick="counter('tambah','${kode}', '${price}')">+</button>
                                        <span class="mx-3 text-dark" id="counted${kode}">0</span>
                                        <button class="rounded-circle border bg-transparent border-success px-2"
                                            onclick="counter('kurang','${kode}', '${price}','${stock}')">-</button>`}
                                </div>
                            </div>
                            <div class="col-lg-3 font-weight-bold font-20 text-right">
                                <span>${price}</span>
                            </div>
                        </div>
                    </div>
                `);
                        counter('tambah', kode, price, stock);
                    }
                } else {
                    alert(`Stok produk ${name} adalah ` + stokString);
                }
            } else {
                errorAlert('Tidak dapat menambahkan obat, Selesaikan dahulu proses Resep');
            }
        }

        function showDescription(kode){
            $.ajax({
                method: 'GET',
                url: '/obat/description/'+kode,
                success: function(response){
                    showDescriptionModal(response.data);
                },
                error: function(error, xhr){
                    errorAlert(xhr.responseText);
                    console.log(error);
                }
            });
        }

        function dismissModal(){
            $('.modal-body.font-18').empty();
            $('#description-modal').modal('hide');
        }

        function showDescriptionModal(data){
            $('#description-modal').modal('show');
            $('.modal-body.font-18').append(data);
        }

        function counter(method, kode, price, stok) {
            const stokString = parseInt($(`#stokProduk${kode}`).text());
            const currentCount = parseInt($(`#counted${kode}`).text());
            let total = parseInt($('#subtotal').text());
            const harga = parseInt(price);
            const stock = parseInt(stok);
            const par = $('#subtotal');

            if (method === 'tambah') {
                if (stokString > 0) {
                    const stokDecreased = stokString - 1;
                    $(`#stokProduk${kode}`).text(parseInt(stokDecreased));
                    const newCount = currentCount === 0 ? 1 : currentCount + 1;
                    $(`#counted${kode}`).text(newCount);
                    const calculated = total + harga;
                    par.text(calculated);
                    calculateGrandTotal();
                    collector(kode, 1, harga);
                } else {
                    alert(`Stok obat ini sudah habis!`);
                }
            } else {
                if (currentCount <= 0) {
                    deleteItem(kode);
                    total = 0;
                    par.text(total);
                    calculateGrandTotal();
                    collector(0, 0, 0);
                    $(`#stokProduk${kode}`).text(parseInt(stock));
                    emptyCollectedItems();
                } else {
                    const stokDecreased = stokString + 1;
                    $(`#stokProduk${kode}`).text(parseInt(stokDecreased));
                    const newCount = currentCount - 1;
                    $(`#counted${kode}`).text(newCount);
                    const calculated = total - harga;
                    par.text(calculated);
                    calculateGrandTotal();
                    collector(kode, -1, harga);
                }
            }

        }

        function deleteItem(kode, price, stok) {
            // console.log(proccessed);
            if (proccessed == 1) {
                errorAlert('Selesaikan proses resep terlebih dahulu');
            } else {
                let cartKode = kode.toString();
                let current = parseInt($(`#counted${cartKode}`).text());
                let harga = parseInt(price);
                let total = parseInt($('#subtotal').text());
                $('#subtotal').text(total -= current * harga);
                $(`#stokProduk${kode}`).text(parseInt(`${stok}`));
                calculateGrandTotal();
                $(`#list${cartKode}`).remove();
                delete cartItems[cartKode];
                proccessed = 0;
            }
        }

        function calculateGrandTotal() {
            let dsc = parseFloat($('#dsc').val());
            let total = parseInt($('#subtotal').text());
            let grandTotal = $('#grandTotal');
            if (isNaN(dsc) || dsc === 0) {
                grandTotal.text(total);
            } else {
                let diskon = dsc / 100;
                let calculatedDiskon = total * diskon;
                let calculated = total - calculatedDiskon;
                grandTotal.text(calculated);
            }
        }

        let collectedItems = {
            kode: [],
            jumlah: [],
            total: []
        };


        function collector(kode, jumlah, harga) {
            let jml = parseInt($(`#counted${kode}`).text());
            let t = jml * harga || parseInt(harga);

            if (collectedItems.kode.includes(kode)) {
                const index = collectedItems.kode.indexOf(kode);
                collectedItems.jumlah[index] += jumlah;
                collectedItems.total[index] += harga;
            } else {
                collectedItems.kode.push(kode);
                collectedItems.jumlah.push(1);
                collectedItems.total.push(harga);
            }


        };

        function closeCheckout() {
            $('.checkout-bar').addClass('d-none');
        }

        function prosesPesanan() {
            if (collectedItems.kode.length == 0) {
                errorAlert('Tidak dapat memproses Pesanan');
            } else {
                let trxCode = $('#trxCode').text();

                if (pasienId == undefined || null) {
                    $('#nama-pasien-modal').modal('show');
                }else{
                    if(kategoriPenjualan == undefined || null){
                        kategoriPenjualan = 'Non Resep';
                    }

                    // if(dokterId == undefined || null){
                    //     dokterId = 0;
                    // }


                    const gt = parseInt($('#grandTotal').text());

                    let dscVal = $('#dsc').val();
                    let dscField;

                    if (dscVal === "") {
                        dscField = 0;
                    } else {
                        dscField = dscVal;
                    }

                    let myForm = new FormData();
                    myForm.append('kode', JSON.stringify(collectedItems.kode));
                    myForm.append('jumlah', JSON.stringify(collectedItems.jumlah));
                    myForm.append('total', collectedItems.total);
                    myForm.append('kodePenjualan', $('#trxCode').text());
                    myForm.append('pasienId', pasienId);
                    myForm.append('dsc', dscField);
                    myForm.append('dokterId', dokterId);
                    myForm.append('kategoriPenjualan', kategoriPenjualan);
                    myForm.append('subtotal', gt);
                    myForm.append('kodeResep', kodeResep);

                    if (collectedItems.jumlah[0] == 0) {
                        errorAlert('Jumlah Produk tidak boleh berjumlah 0')
                    } else {
                        if(pasienId === ''){
                            $('#nama-pasien-modal').modal('show');
                        }else{
                           if(myForm){
                                ajaxUpdate('/resep/antrian/proses', 'POST', myForm);
                                closeCheckout();
                                emptyCollectedItems();
                                if(confirm('Apakah Anda ingin mencetak Invoice ? ') ? launchInvoiceModal(kodeResep, trxCode,kategoriPenjualan) : refreshTable());
                                // for(let i = 0; i < collectedItems.kode.length; i++){
                                //     deleteItem(collectedItems.kode[i]);
                                // }
                            }else{
                                errorAlert('Tidak dapat memproses Resep')
                           }
                        }
                    }
                }

            }
        }

        function emptyCollectedItems() {
            collectedItems = {
                kode: [],
                jumlah: [],
                total: []
            };
            cartItems = {};
            proccessed;
            isnonresep;
            jenisTransaksi = '';
            $('#pasientopwrapper').empty();
            $('#obatCart').empty();
            $('#subtotal').text(0);
            calculateGrandTotal();
        }

        function refreshTable() {
            let number = 0;
            $.ajax({
                url: '/apoteker/resep/not-processed',
                method: 'GET',
                success: function(response) {
                    $('.swiper-wrapper').empty();
                    const hasil = response.data;
                    hasil.forEach(item => {
                        number++;
                        $('.swiper-wrapper').append(`
                        <div class="swiper-slide mx-1">
                            <a href="#" id="daftarResep${item.kode}" onclick="prosesResep('${item.kode}')" data-toggle="modal">
                                <div class="container-slider border rounded-lg px-3">

                                    <div class="row d-flex">
                                        <div class="col-4 text-light d-flex justify-content-center align-items-center">
                                            <div class="font-24 font-weight-bold bg-secondary rounded-circle px-4 py-3">
                                                    ${number}
                                            </div>
                                        </div>
                                        <div class="col-8 text-left py-2">
                                            <p class="font-weight-bold mb-1">
                                                ${item.pasien_id}
                                            </p>
                                            <p class="">
                                                ${item.obat_id} Items
                                            </p>
                                        </div>

                                    </div>

                                </div>
                                </a>
                            </div>

                        `);
                    });
                },
                error: function(error, xhr) {
                    errorAlert(error.responseText);
                    console.log(error);
                    console.log(xhr.responseText);
                }
            });
        }

        function launchInvoiceModal(kode, trxCode, kategori){
            refreshTable();
            if(kategori === 'Non Resep'){
                url = '/apoteker/laporan/penjualan/inovice/'+trxCode;
            }else{
                url = '/apoteker/resep/proses/transaksi/'+kode;
            }
            $.ajax({
                url: url,
                method: 'GET',
                success: function(response){
                    let hasil = response.data[0];
                    let subtotal = 0;
                    $('#invoiceModal').modal('show');
                    $('#createdAt').text(hasil.created_at);
                    const table = $('<table class="table table-bordered"></table>');
                    const tbody = $('<tbody></tbody>');
                    let thead;
                    if(kategori == 'Resep'){
                        $('#namaPasien').text(hasil.namaPasien);
                        $('.modal-title').text('Invoice Transaksi ' + hasil.kode);
                        $('#namaApoteker').text(hasil.namaApoteker);
                        if (hasil.namaDokter !== '' && hasil.namaDokter !== undefined && hasil.namaDokter !== null) {
                            $('#namaDokter').text(hasil.namaDokter);
                        }

                        thead = $('<thead><tr><th>No</th><th>Nama Produk</th><th>Catatan</th><th>Jumlah</th><th>Harga</th></tr></thead>');
                        for (let i = 0; i < hasil.namaProduk.length; i++) {
                            const row1 = $('<tr></tr>');


                            const cell1 = $('<td></td>').text(i + 1);
                            row1.append(cell1);


                            const cell2 =
                            $(`<td>${hasil.namaProduk[i]}</td>`);
                            row1.append(cell2);

                            const cell3 =
                            $(`<td>${hasil.catatan[i]}</td>`);
                            row1.append(cell3);

                            const cell4 = $('<td></td>').text(hasil.jumlah[i]);
                            row1.append(cell4);


                            const cell5 = $('<td></td>').text(formatCurrency(hasil.harga[i]));
                            row1.append(cell5);



                            tbody.append(row1);
                            subtotal += hasil.harga[i] * parseInt(hasil.jumlah[i]);

                            const row2 = $('<tr></tr>');
                            const cell6 = $('<td colspan="4"><div class="font-weight-bold font-18 text-right">Total</div></td>');
                            const cell7 = $('<td></td>').text(formatCurrency(parseInt(subtotal)));
                            row2.append(cell6, cell7);
                            tbody.append(row2);
                        };
                    }else{
                        subtotal = hasil.subtotal;
                        $('#namaApoteker').text(hasil.namaApoteker);
                        $('#namaPasien').text(hasil.namaPasien);
                        $('.modal-title').text('Invoice Transaksi ' + hasil.kode);
                        if (hasil.namaDokter !== '' && hasil.namaDokter !== undefined && hasil.namaDokter !== null) {
                            $('#namaDokter').text(hasil.namaDokter);
                        }

                        thead = $('<thead><tr><th>No</th><th>Nama Produk</th><th>Jumlah</th><th>Harga</th></tr></thead>');
                        for (let i = 0; i < hasil.namaProduk.length; i++) {
                            const row1 = $('<tr rowspan="2"></tr>');

                            const cell1 = $('<td></td>').text(i + 1);
                            row1.append(cell1);

                            const cell2 = $('<td></td>').text(hasil.namaProduk[i]);
                            row1.append(cell2);

                            const cell3 = $('<td></td>').text(hasil.jumlah[i]);
                            row1.append(cell3);

                            const cell4 = $('<td></td>').text(formatCurrency(hasil.harga[i]));
                            row1.append(cell4);

                            tbody.append(row1);

                            const row2 = $('<tr></tr>');
                            const cell5 = $('<td colspan="3"><div class="font-weight-bold font-18 text-right">Total</div></td>');
                            const cell6 = $('<td></td>').text(formatCurrency(parseInt(hasil.subtotal)));
                            row2.append(cell5, cell6);
                            tbody.append(row2);
                        }
                    }

                    table.append(thead);
                    table.append(tbody);
                    $('#total').text(subtotal);
                    $('#table-invoice-wrapper').append(table);

                },
                error: function(error, xhr){
                    errorAlert(xhr.responseText);
                    console.log(error.message);
                }
            });
        }

        function emptyModal(){
            $('#table-invoice-wrapper').empty();
            $('#invoiceModal').modal('hide');
            $('#createdAt, #namaDokter, #namaApoteker, #namaPasien').text('');
        }


    </script>

@endsection
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
