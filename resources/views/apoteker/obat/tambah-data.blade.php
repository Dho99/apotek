@extends('layouts.main')
@section('content')
    <div class="xs-pd-20-10 pd-ltr-20">
        <div class="title pb-20">
            <h2 class="h3 mb-0">{{ $title }}</h2>
        </div>
{{-- @dd($satuan) --}}
        <div class="card-box mb-30">
            <form id="createNewObatForm" onsubmit="submitNewObat(event, '/apoteker/obat/create/store')"
                enctype="multipart/form-data">
                <div class="p-5">
                    <div class="row my-3">
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-3">
                            <label for="kode-obat">Kode Obat</label>
                            <a href="#" class="ml-5" onclick="randomized(event)">Coba Kode Acak?</a>
                            <span class="float-right" data-toggle="tooltip"
                                title="Digunakan Untuk Identifier Sebuah Produk">
                                <i class="icon-copy dw dw-question font-20"></i></span>
                            <input type="text" name="kode-obat" class="form-control" placeholder="Masukkan Kode Obat"
                                id="kodeObat">

                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                            <label for="nama-obat">Nama Obat</label>
                            <span class="float-right" data-toggle="tooltip"
                                title="Digunakan Untuk Identifier Sebuah Produk"><i
                                    class="icon-copy dw dw-question font-20"></i></span>
                            <input type="text" name="nama-obat" id="namaObat" class="form-control"
                                placeholder="Masukkan Nama Obat">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-3">
                            <label for="stock">Golongan Obat</label>
                            <span class="float-right" data-toggle="tooltip"
                                title="Digunakan Untuk Identifier Sebuah Produk"><i
                                    class="icon-copy dw dw-question font-20"></i></span>
                            <select class="form-control" name="golongan_id" id="golongan"
                                multiple="multiple" style="width: 100%">
                                @foreach ($golongan as $item)
                                    <option value="{{ $item->id }}">{{ $item->golongan }}</option>
                                @endforeach

                            </select>

                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                            <label for="exp-date">Tanggal Kadaluarsa</label>
                            <span class="float-right" data-toggle="tooltip"
                                title="Digunakan Untuk Identifier Sebuah Produk">
                                <i class="icon-copy dw dw-question font-20"></i></span>
                            <input class="form-control" id="expDate" placeholder="Exp Date" name="expdate"
                                type="date" />
                        </div>
                    </div>
                    <div class="row my-3">
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-3">
                            <label for="stock">Supplier</label>
                            <span class="float-right" data-toggle="tooltip"
                                title="Digunakan Untuk Identifier Sebuah Produk"><i
                                    class="icon-copy dw dw-question font-20"></i></span>
                            <select name="supplier" id="supplier" style="width: 100%;" class="form-control">
                                <option value=""></option>
                                @foreach ($pemasok as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                            <label for="exp-date">Stok Awal</label>
                            <span class="float-right" data-toggle="tooltip"
                                title="Digunakan Untuk Identifier Sebuah Produk"><i
                                    class="icon-copy dw dw-question font-20"></i></span>
                            <input class="form-control" name="stok" id="stok" placeholder="Stok Awal"
                                type="number"/>
                        </div>
                    </div>
                    <div class="row my-3">
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-3">
                            <label for="profile">Thumbnail Gambar</label>
                            <input type="file" src="" alt="" id="image" class="form-control"
                                name="profile" onchange="previewImage()" value="">
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-3">
                            <img src="" class="img-preview rounded img-fluid border border-success d-none"
                                onclick="if(confirm('Hapus Gambar?')) undoChanges();">
                        </div>

                    </div>
                    <div class="row my-3">
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-3">
                            <label for="">Satuan</label>
                            <select name="" id="satuan" class="form-control">
                                    <option value=""></option>
                                @forelse ($satuan as $item)
                                    <option value="{{$item}}">{{$item}}</option>
                                @empty
                                    {{-- <option value=""></option> --}}
                                @endforelse
                            </select>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-3">
                            <label for="">Harga</label>
                            <input type="text" name="" id="harga" class="form-control">
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-xl-2 col-lg-6 col-md-6 col-sm-6 mb-3">
                            <a href="/apoteker/obat/list"
                                onclick="alert('Perubahan yang anda Lakukan tidak Akan Disimpan !')"
                                class="btn btn-secondary w-100">Kembali</a>
                        </div>
                        <div class="col-xl-2 col-lg-6 col-md-6 col-sm-6 ml-auto">
                            <button type="submit" class="btn btn-primary float-right w-100">Simpan</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script src="{{ asset('vendors/scripts/myScript/tambah-data.js') }}"></script>

    <script>
        $().ready(function() {
            $('#createNewObatForm input').attr('required','required');
            $('#createNewObatForm select').attr('required','required');
            $('#harga').mask('000.000.000', {
                reverse: true
            });
            $('input[type="text"]').keydown(function(e) {
                if (e.keyCode == 13) {
                    e.preventDefault();
                    return false;
                }
            });
            initSel2Tags('#golongan, #supplier, #satuan');
        });




    </script>
@endsection
