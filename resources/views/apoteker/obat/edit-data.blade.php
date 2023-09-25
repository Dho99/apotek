@extends('layouts.main')
@section('content')
    <div class="xs-pd-20-10 pd-ltr-20">
        <div class="title pb-20">
            <h2 class="h3 mb-0" id="title">{{ $title }}</h2>
        </div>

        <div class="card-box mb-30">
            {{-- @dd($datas) --}}
            @foreach ($datas as $item)
                <form id="createNewObatForm" onsubmit="submitNewObat(event, '/apoteker/obat/edit/{{ $item->kode }}')"
                    enctype="multipart/form-data">
                    <div class="p-5">
                        <div class="row my-3">
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-3">
                                <label for="kode-obat">Kode Obat</label>
                                {{-- <a href="#" class="ml-5" onclick="randomized(event)">Coba Kode Acak?</a> --}}
                                <span class="float-right" data-toggle="tooltip"
                                    title="Digunakan Untuk Identifier Sebuah Produk">
                                    <i class="icon-copy dw dw-question font-20"></i></span>
                                <input type="text" name="kode-obat" value="{{ $item->kode }}" disabled
                                    class="form-control" placeholder="Masukkan Kode Obat" id="kodeObat" required>

                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label for="nama-obat">Nama Obat</label>
                                <span class="float-right" data-toggle="tooltip"
                                    title="Digunakan Untuk Identifier Sebuah Produk"><i
                                        class="icon-copy dw dw-question font-20"></i></span>
                                <input type="text" name="nama-obat" id="namaObat" value="{{$item->namaProduk}}" class="form-control"
                                    placeholder="Masukkan Nama Obat" required disabled>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-3">
                                <label for="stock">Golongan Obat</label>
                                <span class="float-right" data-toggle="tooltip"
                                    title="Digunakan Untuk Identifier Sebuah Produk"><i
                                        class="icon-copy dw dw-question font-20"></i></span>
                                <select class="custom-select2 form-control" name="golongan" id="golongan"
                                    multiple="multiple" style="width: 100%" disabled>
                                    @foreach ($golongan as $golonganItem)
                                        <option value="{{ $golonganItem->id }}"
                                            @if (in_array($golonganItem->id, json_decode($item['golongan_id']))) selected @endif>
                                            {{ $golonganItem->golongan }}
                                        </option>
                                    @endforeach
                                </select>

                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label for="exp-date">Tanggal Kadaluarsa</label>
                                <span class="float-right" data-toggle="tooltip"
                                    title="Digunakan Untuk Identifier Sebuah Produk">
                                    <i class="icon-copy dw dw-question font-20"></i></span>
                                <input class="form-control" id="expDate" placeholder="Exp Date" name="expdate" required disabled value="{{$item->expDate}}"
                                    type="date" />
                            </div>
                        </div>
                        <div class="row my-3">
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-3">
                                <label for="stock">Supplier</label>
                                <span class="float-right" data-toggle="tooltip"
                                    title="Digunakan Untuk Identifier Sebuah Produk"><i
                                        class="icon-copy dw dw-question font-20"></i></span>
                                <select name="supplier" id="supplier" class="custom-select2 form-control" style="width: 100%;" required disabled>
                                    <option value="">Pilih Supplier</option>
                                    @foreach ($pemasok as $pemasokItem)
                                        <option value="{{ $pemasokItem->id }}"
                                            @if($pemasokItem->id === json_decode($item['supplier_id']))
                                            selected @endif>{{ $pemasokItem->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label for="exp-date">Stok Awal</label>
                                <span class="float-right" data-toggle="tooltip"
                                    title="Digunakan Untuk Identifier Sebuah Produk"><i
                                        class="icon-copy dw dw-question font-20"></i></span>
                                <input class="form-control" name="stok" id="stok" placeholder="Stok Awal" value="{{$item->stok}}" type="number" required disabled/>
                            </div>
                        </div>
                        <div class="row my-3">
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-3">
                                <label for="profile">Thumbnail Gambar</label>
                                <input type="file" alt="" id="image" class="form-control"
                                    name="profile" onchange="previewImage()" value="" disabled>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-3">
                                <label for="">Satuan</label>
                                <input type="text" name="" value="{{$item->satuan}}" id="satuan" class="form-control" disabled>
                            </div>


                        </div>
                        <div class="row my-3">
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-3">
                                <img src="{{asset('storage/'.$item->image)}}" class="img-preview rounded img-fluid border border-success">
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-3">
                                <label for="">Harga</label>
                                <input type="text" name="" value="{{$item->harga}}" id="harga" class="form-control" disabled>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-xl-2 col-lg-6 col-md-6 col-sm-6 mb-3">
                                <a href="/apoteker/obat/list" id="backToListBtn"
                                    class="btn btn-secondary w-100">Kembali</a>
                            </div>
                            <div class="col-xl-2 col-lg-6 col-md-6 col-sm-6 ml-auto">
                                <button type="button" class="btn btn-primary float-right w-100" onclick="changeMode()" id="changeModeBtn">Edit</button>
                                <button type="submit" class="btn btn-primary float-right w-100 d-none" id="submitBtn">Simpan</button>
                            </div>
                        </div>
                    </div>
                </form>
            @endforeach
        </div>
    </div>
    <script>
        $().ready(function(){
            $('#harga').mask('000,000,000', {reverse: true});
        });
    </script>
    <script src="{{ asset('vendors/scripts/myScript/tambah-data.js') }}"></script>
    <script>
        function changeMode(){
            const changer = $('#changeModeBtn').addClass('d-none');
            const changed = $('#submitBtn').removeClass('d-none');
            const title = $('#title').text('Edit Data Obat');
            const myForm = $('#createNewObatForm input, #createNewObatForm select').removeAttr('disabled');
            $('html, body').animate({ scrollTop: 0 }, 800);
            $('#backToListBtn').attr('onclick', "alert('Perubahan yang anda Lakukan tidak Akan Disimpan !')");
        }
    </script>

@endsection
