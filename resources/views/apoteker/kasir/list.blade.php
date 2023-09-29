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
                    {{-- @dd($datas) --}}

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
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" role="tab" onclick="filterKatalog('Tablet')"
                        aria-selected="false">Tablet</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" role="tab" onclick="filterKatalog('Kapsul')"
                        aria-selected="false">Kapsul</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" role="tab" onclick="filterKatalog('Pill')"
                        aria-selected="false">Pill</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" role="tab" onclick="filterKatalog('Obat Cair')"
                        aria-selected="false">Obat
                        Cair</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" role="tab" onclick="filterKatalog('Oles')"
                        aria-selected="false">Oles</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" onclick="filterKatalog('Injeksi')" role="tab"
                        aria-selected="false">Injeksi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" onclick="filterKatalog('Lain - Lain')" data-toggle="tab" role="tab"
                        aria-selected="false">Lain -
                        Lain</a>
                </li>
                <li class="nav-item ml-auto mb-2">

                </li>
            </ul>



            <div class="tab-pane fade show active mt-2" id="contact2" role="tabpanel">

                <div class="row clearfix" id="katalogCard">

                </div>
            </div>


        </div>

        {{-- @dd($pasiens) --}}


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




        <script>

        let cartItems = {};
        let randCodeCollector;
        let proccessed = 0;
        let isnonresep = 0;

        $().ready(function() {
            filterKatalog('Semua');
            refreshTable();
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
                                <div class="col-lg-3 col-md-6 col-sm-12 mb-30" onclick="checkout('${kode}', '${image}', '${namaProduk}', '${harga}', '${stok}')">
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
            console.log(isnonresep);
            if (proccessed == 0 && isnonresep == 0) {
                $.ajax({
                    url: '/apoteker/resep/proses/transaksi/' + kode,
                    method: 'GET',
                    success: function(response) {
                        cartItems[`${kode}`] = 1;
                        const hasil = response.data[0];
                        $('#pasientopwrapper').append(`
                    <div class="row container" style="margin-top: 80px; margin-bottom: -40px;">
                        <div class="col-12">
                            <div>Nama Pasien : <span class="font-weight-bold" id="namaPasien">${hasil.namaPasien}</span></div>
                            <div>Diproses Pada : <span class="font-weight-bold">${hasil.created_at}</span></div>
                            <div>Dokter Resep : <span class="font-weight-bold" id="namaDokter">${hasil.namaDokter}</span></div>
                            <div>Kode Resep : <span class="font-weight-bold" id="kodeResep">${hasil.kode}</span></div>
                        </div>
                    </div>
                    `);

                        const datas = {
                            kodeProduk: hasil.kodeProduk,
                            imageProduk: hasil.image,
                            namaProduk: hasil.namaProduk,
                            hargaProduk: hasil.harga,
                            stokProduk: hasil.stok
                        }

                        for (let i = 0; i < datas.kodeProduk.length; i++) {
                            let kode = datas.kodeProduk[i];
                            let image = datas.imageProduk[i];
                            let name = datas.namaProduk[i];
                            let price = datas.hargaProduk[i];
                            let stock = datas.stokProduk[i];

                            checkout(kode, image, name, price, stock);
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

        function checkout(kode, image, name, price, stock) {
            const stokString = parseInt($(`#stokProduk${kode}`).text());
            if (proccessed != 1) {
                isnonresep = 1;
                console.log(isnonresep);
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
                                <span>
                                    <a href="${imageUrl}">Detail / Deskripsi</a>
                                </span>
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
                let userId = '';
                let dokterId = '';
                let pasienId = '';

                let kategoriPenjualan = 'Non Resep';
                const kode = collectedItems.kode;
                let jumlah = collectedItems.jumlah;
                const total = collectedItems.total;
                const kodePenjualan = $('#trxCode').text();
                let kodeResep = $('#kodeResep').text();

                if ($('#namaPasien').text() !== '' || $('#namaDokter').text() !== '') {
                    pasienId = $('#namaPasien').text();
                    dokterId = $('#namaDokter').text();
                    kategoriPenjualan = 'Resep';
                }else{
                    $('#nama-pasien-modal').modal('show');
                }

                const gt = parseInt($('#grandTotal').text());

                let dscVal = $('#dsc').val();
                let dscField;

                if (dscVal === "") {
                    dscField = 0;
                } else {
                    dscField = dscVal;
                }

                if (jumlah[0] == 0) {
                    errorAlert('Jumlah Produk tidak boleh berjumlah 0')
                } else {
                    if(pasienId === ''){
                        $('#nama-pasien-modal').modal('show');
                    }else{
                        // console.log(dokterId);
                        $.ajax({
                            url: '/resep/antrian/proses/',
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                kode: kode,
                                kodeResep: kodeResep,
                                jumlah: jumlah,
                                total: total,
                                kodePenjualan: kodePenjualan,
                                pasienId: pasienId,
                                dsc: dscField,
                                dokterId: dokterId,
                                kategoriPenjualan: kategoriPenjualan,
                                subtotal: gt
                            },
                            success: function(response) {
                                successAlert(response.message);
                                emptyCollectedItems();
                                closeCheckout();
                                $('.row.container').remove();
                                $('#daftarResep').show();
                                dokterId = '';
                                pasienId = '';
                            },
                            error: function(xhr, status, error) {
                                console.error("Error:", error);
                                console.log("Response:", xhr.responseText);
                            },
                        });
                    }
                }
            }
        }

        function emptyCollectedItems() {
            collectedItems = {
                kode: [],
                jumlah: [],
                total: 0
            }
            jenisTransaksi = '';
        }

        function refreshTable() {
            $.ajax({
                url: '/apoteker/resep/not-processed',
                method: 'GET',
                success: function(response) {
                    const hasil = response.data;
                    let number = 0;
                    hasil.forEach(item => {
                        number++;
                        console.log(item);
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
    </script>

@endsection
