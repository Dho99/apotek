@extends('layouts.main')
@section('content')
    <div class="xs-pd-20-10 pd-ltr-20">
        <div class="title pb-20">
            <h2 class="h3 mb-0">{{ $title }}</h2>
        </div>
        {{-- @dd($kategoriDokter) --}}
        <div class="card-box mb-30">
            <div class="pd-20">
                <label for="">Filter Dokter Berdasarkan Kategori</label>
                <div class="row mt-3 d-flex">
                    <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 pb-3 d-flex">
                        <select id="filterDokter" class="form-control rounded-right">
                            <option value="">Filter Dokter</option>
                            @foreach ($kategoriDokter as $item)
                                <option value="{{ $item->kategoriDokter }}">{{ $item->kategoriDokter }}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-info rounded-left">Cari</button>
                    </div>
                    <div class="col-xl-3 col-lg-12 col-md-12 col-sm-12 ml-auto">
                        <a href="#" class="btn btn-outline-success w-100">
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
                            <th class="table-plus datatable-nosort">No</th>
                            <th>Kode</th>
                            <th>Nama Dokter</th>
                            <th>Kategori Dokter</th>
                            <th class="datatable-nosort">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dokter as $item)
                            <tr>
                                <td class="table-plus">{{ $loop->iteration }}</td>
                                <td>{{ $item->kode }}</td>
                                <td>{{ $item->nama }}</td>
                                <td>{{ $item->kategoriDokter }}</td>
                                <td>
                                    <div class="dropdown">
                                        <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                                            href="#" role="button" data-toggle="dropdown">
                                            <i class="dw dw-more"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                            <a class="dropdown-item" href="#"><i class="dw dw-eye"></i> View</a>
                                            <a class="dropdown-item" href="#"><i class="dw dw-edit2"></i> Edit</a>
                                            <a class="dropdown-item text-danger" href="#"><i
                                                    class="dw dw-delete-3"></i>
                                                Delete</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
