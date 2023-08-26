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
    function getDataByKode() {
        const kode = $('#kodeProduk').val();
        if (kode !== "") {
            $.ajax({
                url: '/apoteker/obat/add/stock/' + kode,
                method: 'GET',
                success: function(response) {
                    const data = response.data;
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
                                <input class="form-control date-picker" placeholder="Exp Date" required type="text" id="expDate"/>
                                <p class='text-danger d-none' id="cekExpDate">Jumlah stok tidak boleh Kosong</p>
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
        const csrfToken = $('meta[name="csrf-token"]').attr("content");
        if (stock <= 0) {
            // alert('Stok tidak boleh kurang dari 0');
            $('#cekStok').removeClass('d-none');
        } else if (expDate == '') {
            $('#cekExpDate').removeClass('d-none');
        } else {
            let myForm = new FormData();
            myForm.append("kode", kode);
            myForm.append("stok", stock);
            myForm.append("expDate", expDate);
            $.ajax({
                url: '/apoteker/obat/add/update/stock/' + kode,
                headers: {
                    "X-CSRF-TOKEN": csrfToken
                },
                contentType: false,
                processData: false,
                method: 'POST',
                data: myForm,
                success: function(response) {
                    successAlert(response.message);
                    $('#kodeProduk').val(null).trigger('change');
                },
                error: function(error, xhr) {
                    console.log(error.message);
                    console.log(xhr.responseText);
                }
            });
        }
    }
</script>
