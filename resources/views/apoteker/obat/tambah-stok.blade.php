@extends('layouts.main')
@section('content')
    <div class="xs-pd-20-10 pd-ltr-20">
        <div class="title pb-20">
            <h2 class="h3 mb-0">{{ $title }}</h2>
        </div>

        <div class="card-box mb-30">
            <div class="p-5">
                <form action="javascript:void(0)" onsubmit="increase(event);">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-3">
                            <label for="kode-obat">Kode Obat</label>
                            <span class="float-right" data-toggle="tooltip"
                                title="Digunakan Untuk Identifier Sebuah Produk"><i
                                    class="icon-copy dw dw-question font-20"></i></span>
                            <select id="kodeProduk" class="custom-select2 form-control" style="width: 100%;" required
                                onchange="getDataByKode()">
                                <option value="">Cari Kode Produk</option>
                                @foreach ($data as $item)
                                    <option value="{{ $item->kode }}">{{ $item->kode }} | {{ $item->namaProduk }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div id="inputAfterSearchKode">

                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

<script>
   const kas = () => {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: '/apoteker/count/kas/dashboard',
            method: 'GET',
            success: function(response){
                const data = response.kas;
                resolve(data);
            },
            error: function(error, xhr){
                errorAlert(xhr.responseText);
                console.log(error.message);
                reject(error);
            }
        });
    });
   }

    let hargaProduk;
    let jumlahProduk;
    function getDataByKode() {
        const kode = $('#kodeProduk').val();
        if (kode !== "") {
            $.ajax({
                url: '/apoteker/obat/add/stock/' + kode,
                method: 'GET',
                success: function(response) {
                    const data = response.data;
                    hargaProduk = data.harga;
                    $('#inputAfterSearchKode').empty().append(`
                    <div class="row mb-3">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <label for="nama-obat">Nama Obat</label>
                                <span class="float-right" data-toggle="tooltip"
                                    title="Digunakan Untuk Identifier Sebuah Produk"><i
                                        class="icon-copy dw dw-question font-20"></i></span>
                                <input type="text" name="nama-obat" value="${data.namaProduk}" class="form-control" required disabled
                                    placeholder="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-3">
                                <label for="stock">Stock</label>
                                <span class="float-right" data-toggle="tooltip"
                                    title="Digunakan Untuk Identifier Sebuah Produk"><i
                                        class="icon-copy dw dw-question font-20"></i></span>
                                <input type="number" name="stock" id="stock" class="form-control" required
                                    placeholder="Tambah Jumlah Stok" value="">
                                    <p class='text-danger d-none' id="cekStok">Jumlah stok tidak boleh kurang dari 0</p>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label for="exp-date">Tanggal Kadaluarsa</label>
                                <span class="float-right" data-toggle="tooltip"
                                    title="Digunakan Untuk Identifier Sebuah Produk"><i
                                        class="icon-copy dw dw-question font-20"></i></span>
                                <input class="form-control" placeholder="Exp Date" required type="date" id="expDate"/>
                                <p class='text-danger d-none' id="cekExpDate">Exp date tidak boleh kosong</p>
                            </div>
                        </div>
                        <div class="row my-3">
                            <div class="col-xl-2 col-lg-6 col-md-12 col-sm-12 mb-3">
                                <a href="/apoteker/obat/list"
                                    onclick="alert('Perubahan yang anda Lakukan tidak Akan Disimpan !')"
                                    class="btn btn-secondary w-100">Kembali</a>
                            </div>
                            <div class="col-xl-2 col-lg-6 col-md-12 col-sm-12 ml-auto">
                                <button type="submit" id="submitStokBtn" class="btn btn-primary float-right  w-100">Simpan</button>
                            </div>
                        </div>
                    </div>
                    `);
                },
                error: function(error) {
                    console.log(error.message);
                }
            })
        } else {
            $('#inputAfterSearchKode').empty();
        }
    }

    function increase(event) {
        event.preventDefault();
        const kode = $('#kodeProduk').val();
        const stock = $("#stock").val();
        const expDate = $('#expDate').val();
        jumlahProduk = stock;
        let total = parseInt(jumlahProduk) * parseInt(hargaProduk);
        let totalKas = 0;
        kas().then(result => {
            totalKas = result;
            if (stock <= 0) {
                alert('Stok tidak boleh kurang dari 0');
                $('#cekStok').removeClass('d-none');
            }else if(totalKas < total){
                errorAlert('Kas Apotek tidak mencukupi Pembelanjaan, Jumlah kas kurang '+formatCurrency(total - totalKas));
            } else if (expDate == '') {
                $('#cekExpDate').removeClass('d-none');
            } else {
                let myForm = new FormData();
                myForm.append("kode", kode);
                myForm.append("stok", stock);
                myForm.append("expDate", expDate);

                const url = '/apoteker/obat/add/update/stock/' + kode;
                ajaxUpdate(url, 'POST', myForm);
                setTimeout(() => {
                    $('#kodeProduk').val(null).trigger('change');
                }, 1000);
            }
        });
    }
</script>
