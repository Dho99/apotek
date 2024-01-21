@extends('layouts.main')
@section('content')
    <div class="xs-pd-20-10 pd-ltr-20">
        <div class="title pb-20">
            <h2 class="h3 mb-0" id="title">{{ $title }}</h2>
        </div>

        @php
            $now = \Illuminate\Support\Carbon::now();
            $expTime = $now->format('Y-m-d');
            @endphp
        <div class="card-box mb-30">
            {{-- @dd($datas) --}}
            @foreach ($datas as $item)
            <form id="createNewObatForm" onsubmit="submitNewObat(event, '/apoteker/obat/edit/{{ $item->kode }}')"
                enctype="multipart/form-data">
                <div class="px-4 py-2">
                        @if($expTime == $item->expDate)
                        <div id="notification" class="pt-3 pb-1">
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Obat sudah Kadaluarsa</strong> Perbarui tanggal kadaluarsanya segera
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                        </div>
                        @endif
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
                                <select class="form-control" name="golongan" id="golongan"
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
                                <input class="form-control {{$item->expDate ===$expTime ? 'text-danger form-control-danger' : ''}} " id="expDate" placeholder="Exp Date" name="expdate" required disabled value="{{$item->expDate}}"
                                    type="date" />
                            </div>
                        </div>
                        <div class="row my-3">
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-3">
                                <label for="stock">Supplier</label>
                                <span class="float-right" data-toggle="tooltip"
                                    title="Digunakan Untuk Identifier Sebuah Produk"><i
                                        class="icon-copy dw dw-question font-20"></i></span>
                                <select name="supplier" id="supplier" class="form-control" style="width: 100%;" required disabled>
                                    <option value="">Pilih Supplier</option>
                                    @foreach ($pemasok as $pemasokItem)
                                    <option value="{{ $pemasokItem->id }}"
                                        {{-- @dd($pemasokItem->id) --}}
                                            @if($pemasokItem->id === $item->supplier_id)
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
                                <select name="" id="satuan" class="form-control" disabled>
                                    @forelse ($satuan as $itemSatuan)
                                        <option value="{{$itemSatuan}}" {{$itemSatuan == $item->satuan ? 'selected' : ''}}>{{$itemSatuan}}</option>
                                     @empty
                                        <option value=""></option>
                                    @endforelse
                                </select>
                            </div>
                        </div>
                        <div class="row my-3">
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-3">
                                <img src="{{asset('storage/'.$item->image)}}" class="img-preview rounded img-fluid border border-success">
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-3">
                                <label for="">Harga Beli</label>
                                <input type="text" name="" value="{{$item->hargabeli}}" id="hargabeli" class="form-control" disabled>
                            </div>
                        </div>
                        <div class="row my-3">
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-3">
                                <label for="">Harga Jual</label>
                                <input type="text" name="" value="{{$item->harga}}" id="harga" class="form-control" disabled>
                            </div>
                        </div>
                        <div class="row my-3 px-3" id="deskripsiField">
                           <article>
                            {!! $item->deskripsi !!}
                           </article>
                        </div>
                        <div class="row mt-4">
                            <div class="col-xl-2 col-lg-6 col-md-6 col-sm-6 mb-3">
                                <a href="/apoteker/obat/list" id="backToListBtn"
                                    class="btn btn-secondary w-100 text-light">Kembali</a>
                            </div>
                            <div class="col-xl-2 col-lg-6 col-md-6 col-sm-6 ml-auto">
                                <button type="button" class="btn btn-primary float-right w-100" id="changeModeBtn">Edit</button>
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
            initSel2Tags('#golongan, #supplier, #satuan');
            $('#harga, #hargabeli').mask('000,000,000', {reverse: true});
            $('input[type="text"]').keydown(function(e) {
                if (e.keyCode == 13) {
                    e.preventDefault();
                    return false;
                }
            });
        });
    </script>
    <script src="{{ asset('vendors/scripts/myScript/tambah-data.js') }}"></script>
    <script>
        let isEdit;
        $('#changeModeBtn').on('click', function(){
            const changer = $('#changeModeBtn').addClass('d-none');
            const changed = $('#submitBtn').removeClass('d-none');
            const title = $('#title').text('Edit Data Obat');
            const myForm = $('#createNewObatForm input, #createNewObatForm select').removeAttr('disabled');
            $('html, body').animate({ scrollTop: 0 }, 800);
            isEdit = true;
            $('#backToListBtn').removeAttr('href');
            $('#deskripsiField').empty().append(`
                <input id="descriptionInput" value="{{$item->deskripsi}}" placeholder="Silakan masukkan deskripsi obat disini" type="hidden" name="content">
                <trix-editor input="descriptionInput" class="form-control rounded-0" style="height: auto;"></trix-editor>
            `);
        });

        $('#backToListBtn').on('click', function(){
            if(isEdit){
                if(confirm('Perubahan yang anda buat tidak akan disimpan')){
                    window.location.href = '/apoteker/obat/list';
                    isEdit = false;
                }else{
                    alert('Anda sedang berada di mode Edit data');
                }
            }
        });

    </script>

@endsection
