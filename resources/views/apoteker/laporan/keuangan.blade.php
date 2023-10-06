@extends('layouts.main')
@section('content')

    <div class="xs-pd-20-10 pd-ltr-20">
        <div class="title pb-20 d-flex">
            <h2 class="h3 mb-0">{{ $title }}</h2>
            <button class="btn btn-success ml-auto" onclick="tambahLaporan()">
                <span class="icon-copy dw dw-add"></span> Tambah Data
            </button>
        </div>

        {{-- @dd($keterangan) --}}
        <div class="card-box mb-20">
            <div class="pd-20">
                <div class="row d-flex px-3">
                    <div class="font-20 px-3">Total Saldo</div>
                    <div class="font-24 font-weight-bold" id="saldoText"></div>
                </div>
            </div>
        </div>
        <div class="card-box mb-5">
            <div class="pd-20">
                <div class="row d-flex mb-3">
                    <div class="col-lg-8 font-20">
                        Filter data berdasarkan <span id="kategoriTitle"></span>
                    </div>
                    <div class="col-lg-4">
                        <select name="" id="categoryKeuangan"
                            class="form-control" onchange="refreshTable()">
                            <option value="" selected>Semua</option>
                            @foreach ($kategori as $item)
                                <option value="{{ $item }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-8 font-20">
                        Urutkan Per
                    </div>
                    <div class="col-lg-4 d-flex">
                        <select name="" id="orByMo" class="form-control mx-1" onchange="refreshTable()">
                            <option value="">Bulan</option>
                            @foreach ($perBulan as $key => $item)
                                <option value="{{$key}}">{{$key}}</option>
                            @endforeach
                        </select>

                        <select name="" id="orByYear" class="form-control mx-1" onchange="refreshTable()">
                            <option value="">Tahun</option>
                            @foreach ($perTahun as $key => $item)
                                <option value="{{$key}}">{{$key}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-box mb-30">
            <div class="pd-20">
                    <table class="data-table table" id="myKeuanganTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Transaksi oleh</th>
                                <th>Keterangan</th>
                                <th>Kategori</th>
                                <th>Jumlah</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="tambahLaporanModal" data-backdrop="static" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content shadow">
                <div class="modal-body">
                    <div class="modal-title text-center font-weight-bold font-20" id="title"></div>
                    <form action="" onsubmit="createLaporan(event)" id="myLaporanForm">
                        <div class="form-group mt-4" id="last">
                        <label class="font-weight-bold d-flex">Keterangan</label>
                            <input class="form-control" value="" type="text" id="keterangan" disabled required />
                        </div>
                        <div class="mt-4 mb-2 d-flex">
                            <button class="btn btn-secondary" type="button" onclick="emptyModal()">Kembali</button>
                            <button class="btn btn-info ml-auto d-none" type="button"
                                onclick="changeToEdit('#dataUserForm')">Edit</button>
                            <button class="btn btn-success ml-auto d-none" id="updateBtn" type="submit">Update</button>
                            <button class="btn btn-success ml-auto d-none" id="createBtn" type="submit">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="{{asset('vendors/scripts/myScript/lapKeu.js')}}"></script>

    <script>
        $().ready(function(){
            $('#myPenjualanTable')
        })
    </script>
@endsection
