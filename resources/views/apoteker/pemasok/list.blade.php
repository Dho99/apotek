@extends('layouts.main')
@section('content')
    <link rel="stylesheet" type="text/css" href="{{asset('src/plugins/jquery-asColorPicker/dist/css/asColorPicker.css')}}" />
    <div class="xs-pd-20-10 pd-ltr-20">
        <div class="title pb-20">
            <h2 class="h3 mb-0">{{ $title }}</h2>
        </div>

        <div class="card-box mb-30">
            <div class="pd-20">
                <label>Cari Berdasarkan Tanggal</label>
                <div class="row mt-3">
                    <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 pb-3 d-flex mb-2">
                        <div class="form-group  row w-100 m-auto">
                            <input
                                class="form-control date-picker col-xl-5 col-lg-6 col-md-12 col-sm-12 mb-1"
                                placeholder="Dari Tanggal"
                                type="text"
                            />
                            <input
                                class="form-control date-picker col-xl-5 col-lg-6 col-md-12 col-sm-12 mb-1"
                                placeholder="Sampai Tanggal"
                                type="text"
                            />
                            <div class="col-xl-2 col-lg-2 col-md-12 col-sm-12 p-0">
                                <button class="btn btn-primary w-100" type="button" id="filterSupplier">Cari</button>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-2 col-lg-12 col-md-12 col-sm-12 ml-auto">
                        <a href="#" class="btn btn-outline-success w-100 mb-3">
                            <span class="icon-copy dw dw-add"></span>
                            Tambah Data
                        </a>
                    </div>
                </div>
            </div>
            <div class="pb-20">
                <table class="data-table table nowrap">
                    <thead>
                        <tr>
                            <th class="table-plus datatable-nosort">Kode</th>
                            <th>Nama Obat</th>
                            <th>Pemasok</th>
                            <th>Kategori</th>
                            <th>Stock</th>
                            <th>Tanggal Masuk</th>
                            <th class="datatable-nosort">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="table-plus">A001</td>
                            <td>Paracetamol</td>
                            <td>PT. Mercy Indah</td>
                            <td>Antipretik</td>
                            <td>66</td>
                            <td>22/22/2222</td>
                            <td>
                                <div class="dropdown">
                                    <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                                        href="#" role="button" data-toggle="dropdown">
                                        <i class="dw dw-more"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                        <a class="dropdown-item" href="#"><i class="dw dw-eye"></i> View</a>
                                        <a class="dropdown-item" href="#"><i class="dw dw-edit2"></i> Edit</a>
                                        <a class="dropdown-item text-danger" href="#"><i class="dw dw-delete-3"></i>
                                            Delete</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
