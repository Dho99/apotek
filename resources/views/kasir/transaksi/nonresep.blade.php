@extends('layouts.main')
@section('content')
    <div class="xs-pd-20-10 pd-ltr-20">
        <div class="checkout-bar d-none">
            <div class="bg-lightgreen py-3 px-2 border-bottom checkout-bar-top align-items-center d-flex">
                <button class="font-weight-bold btn btn-sm btn-outline-secondary " onclick="closeCheckout()">
                    Tutup
                </button>
                {{-- <button class="font-weight-bold btn btn-sm btn-outline-danger " onclick="emptyCart()">
                    Clear
                </button> --}}
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


        <h5 class="h4 mb-20">Katalog Obat</h5>

        <div class="tab my-2">
            <ul class="nav nav-tabs customtab border-0" role="tablist">
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


            </ul>

            <div class="tab-pane fade show active mt-2" id="contact2" role="tabpanel">

                <div class="row clearfix" id="katalogCard">

                </div>
            </div>


        </div>
        <script>
            $().ready(function(){
                filterKatalog('Semua');
            });
        </script>
    @endsection
    <script>
        function emptyCart() {
            $('#obatCart').empty();
            cartItems = [];
            randCodeCollector = undefined;
            $('#trxCode').text('');
            $('#subtotal').text(0);
            calculateGrandTotal();
            filterKatalog('Semua');
            $('.nav.nav-tabs.customtab').html(`
            <ul class="nav nav-tabs customtab border-0" role="tablist">
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
                    <input type="text" name="" id="" placeholder="Search Item" class="form-control">
                </li>

            </ul>
            `);
        }

        function filterKatalog(satuan) {
            const hasil = `${satuan}`;
            $.ajax({
                url: '/katalog/filter/' + hasil,
                method: 'GET',
                success: function(response) {
                    const hasil = response.data;
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
                                        <div class="card-body">
                                            <h5 class="card-title weight-500">${namaProduk}
                                                <span class="float-right">${harga}</span>
                                            </h5>
                                            <p class="card-text">
                                                <span class="float-left">
                                                    ${satuan}
                                                </span>
                                                <span class="float-right" id="stokProduk${kode}">
                                                    ${stok}
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            `);
                        });
                    }
                },
                error: function(xhr, textStatus, errorThrown) {
                    console.log(xhr.responseText); // Perbaikan di sini: menggunakan xhr.responseText
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            });
        }

        let cartItems = {};
        let randCodeCollector;

        function checkout(kode, image, name, price, stock) {
            const stokString = parseInt($(`#stokProduk${kode}`).text());
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
                                    Paracetamol
                                </span>
                                <span>
                                    <a href="${imageUrl}">Detail / Deskripsi</a>
                                </span>
                                <div class="counter-bar font-20 pt-2 d-flex m-auto text-green align-items-center">
                                    <button class="rounded-circle bg-transparent border border-success px-2"
                                        onclick="counter('tambah','${kode}', '${price}')">+</button>
                                    <span class="mx-3 text-dark" id="counted${kode}">0</span>
                                    <button class="rounded-circle border bg-transparent border-success px-2"
                                        onclick="counter('kurang','${kode}', '${price}','${stock}')">-</button>
                                </div>
                            </div>
                            <div class="col-lg-3 font-weight-bold font-20 text-right">
                                <span>${price}</span>
                                <button class="btn btn-danger" onclick="deleteItem('${kode}','${price}','${stock}')"><i class="icon-copy dw dw-trash"></i></button>
                            </div>
                        </div>
                    </div>
                `);
                    counter('tambah', kode, price, stock);
                }
            } else {
                alert(`Stok produk ${name} adalah ` + stokString);
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
            let cartKode = kode.toString();
            let current = parseInt($(`#counted${cartKode}`).text());
            let harga = parseInt(price);
            let total = parseInt($('#subtotal').text());
            $('#subtotal').text(total -= current * harga);
            $(`#stokProduk${kode}`).text(parseInt(`${stok}`));
            calculateGrandTotal();
            $(`#list${cartKode}`).remove();
            delete cartItems[cartKode];
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
            const kode = collectedItems.kode;
            const jumlah = collectedItems.jumlah;
            const total = collectedItems.total;
            const kodePenjualan = $('#trxCode').text();

            let userId = '';
            let dokterId = '';
            let kategoriPenjualan = 'Non Resep';

            if ($('#userCode').text() !== undefined && $('#dokterCode').text() !== undefined) {
                userId = $('#userCode').text();
                dokterId = $('#dokterCode').text();
                let kategoriPenjualan = 'Resep';
            }

            const gt = parseInt($('#grandTotal').text());

            let dscVal = $('#dsc').val();
            let dscField;

            if (dscVal === "") {
                dscField = 0;
            } else {
                dscField = dscVal;
            }


            $.ajax({
                url: '/resep/antrian/proses/',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    kode: kode,
                    jumlah: jumlah,
                    total: total,
                    kodePenjualan: kodePenjualan,
                    userId: userId,
                    dsc: dscField,
                    dokterId: dokterId,
                    kategoriPenjualan: kategoriPenjualan,
                    subtotal: gt
                },
                success: function(response) {
                    successAlert(response.message);
                    // console.log(response.data);
                    emptyCart();
                    closeCheckout();
                    emptyCollectedItems();
                },
                error: function(xhr, status, error) {
                    console.error("Error:", error);
                    console.log("Response:", xhr.responseText);
                },
            });
        }

        function emptyCollectedItems(){
            collectedItems = {
                kode: [],
                jumlah: [],
                total: 0
            }
        }
    </script>
