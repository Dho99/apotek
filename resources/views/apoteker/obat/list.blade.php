@extends('layouts.main')
@section('content')
    <div class="xs-pd-20-10 pd-ltr-20">
        <div class="title pb-20">
            <h2 class="h3 mb-0">{{ $title }}</h2>
        </div>
        <div class="card-box mb-30">
            <div class="pd-20">
                <label for="">Filter Obat berdasarkan Kategori <span id="desc"></span> </label>
                <div class="row d-flex mt-3">
                    <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 pb-3 d-flex">
                        <select id="filterSupplier" class="custom-select2 form-control rounded-right" onchange="refreshTable()">
                            <option value="Semua">Semua</option>
                            @foreach ($kategori as $item)
                                <option value="{{ $item->golongan }}">{{ $item->golongan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-xl-3 col-lg-12 col-md-12 col-sm-12 ml-auto">
                        <a href="/apoteker/obat/create" class="btn btn-outline-success w-100">
                            <span class="icon-copy dw dw-add"></span>
                            Tambah Data
                        </a>
                    </div>
                </div>
            </div>
            <div class="pb-20">
                <table class="data-table table nowrap" id="myObatTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama Obat</th>
                            <th>Kategori</th>
                            <th>Stock</th>
                            <th>Unit</th>
                            <th>Pemasok</th>
                            <th class="datatable-nosort">Action</th>
                        </tr>
                    </thead>

                </table>
            </div>
        </div>
    </div>
    <script>
        $().ready(function() {
            refreshTable();
        });
    </script>
@endsection
<script>
    function refreshTable() {
        const kategori = $('#filterSupplier').val();
        const url = '/'+kategori;
        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                updateTable('#myObatTable', response.data, [
                    {
                        render: function(data, type, row, meta){
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: "kode"
                    },
                    {
                        data: "namaProduk"
                    },
                    {
                        data: "golongan"
                    },
                    {
                        data: "stok"
                    },
                    {
                        data: "satuan"
                    },
                    {
                        data: "supplier"
                    },
                    {
                        render: function(data, type, row) {
                            // Masukkan kode HTML aksi sesuai kebutuhan
                            return `
                                <div class="dropdown">
                                    <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                                        href="#" role="button" data-toggle="dropdown">
                                        <i class="dw dw-more"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                        <a class="dropdown-item"  href="/apoteker/obat/show/${row.kode}">
                                            <i class="dw dw-eye"></i> View
                                        </a>
                                        <a class="dropdown-item text-danger" onclick="deleteDataObat('${row.kode}')"><i class="dw dw-delete-3"></i>
                                            Delete</a>
                                    </div>
                                </div>
                                `;
                        }
                    }
                ]);
            },
            error: function(error, xhr) {
                console.log(error);
                console.log(xhr.responseText);
            }
        });
        if (kategori === 'Semua') {
            const desc = $("#desc").html('');
        } else {
            const desc = $("#desc").html(`${kategori}`);
        }
    };


    function deleteDataObat(kode) {
        const kodeForDelete = kode;
        const url = '/apoteker/obat/list/delete/';
        deleteData(url, kodeForDelete);
    }
</script>
