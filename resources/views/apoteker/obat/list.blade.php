@extends('layouts.main')
@section('content')
    <div class="xs-pd-20-10 pd-ltr-20">
        <div class="title pb-20">
            <h2 class="h3 mb-0">{{ $title }}</h2>
        </div>
        <div class="modal fade bs-example-modal" id="edit-obat-modal" tabindex="-1" role="dialog"
            aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myLargeModalLabel">
                            Edit Data Produk
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                            ×
                        </button>
                    </div>
                    <div class="modal-body">

                        <p id="modalKode"></p>
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipisicing
                            elit, sed do eiusmod tempor incididunt ut labore et
                            dolore magna aliqua. Ut enim ad minim veniam, quis
                            nostrud exercitation ullamco laboris nisi ut aliquip
                            ex ea commodo consequat. Duis aute irure dolor in
                            reprehenderit in voluptate velit esse cillum dolore eu
                            fugiat nulla pariatur. Excepteur sint occaecat
                            cupidatat non proident, sunt in culpa qui officia
                            deserunt mollit anim id est laborum.
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            Close
                        </button>
                        <button type="button" class="btn btn-primary">
                            Save changes
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-box mb-30">
            <div class="pd-20">
                <label for="">Filter Obat berdasarkan Kategori <span id="desc"></span> </label>
                <div class="row d-flex mt-3">
                    <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 pb-3 d-flex">
                        <select id="filterSupplier" class="custom-select2 form-control rounded-right">
                            <option value="Semua">Semua</option>
                            @foreach ($kategori as $item)
                                <option value="{{ $item->golongan }}">{{ $item->golongan }}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-info rounded-left" onclick="filterData()">Cari</button>
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
                            <th class="table-plus datatable-nosort">Kode</th>
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
@endsection
<script>

    function filterData() {
        const kategori = $('#filterSupplier').val();
        if (kategori != "") {
            getData(kategori)
        }
    }

    function getData(kategori) {
        let myTable = $('#myObatTable').DataTable();
        const url = `/${kategori}`;
        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                // console.log(response.data);
                myTable.clear().destroy();
                myTable = $('#myObatTable').DataTable({
                    "language": {
                        searchPlaceholder: 'Cari apa saja Disini',
                    },
                    autoWidth: false,
                    responsive: true,
                    data: response.data, // Ganti dengan properti data yang sesuai dalam respons
                    columns: [{
                            title: "Kode",
                            data: "kode"
                        },
                        {
                            title: "Nama Obat",
                            data: "namaProduk"
                        },
                        {
                            title: "Kategori",
                            data: "golongan"
                        },
                        {
                            title: "Satuan",
                            data: "satuan"
                        },
                        {
                            title: "Stok",
                            data: "stok"
                        },
                        {
                            title: "Supplier",
                            data: "supplier.nama"
                        },
                        {
                            title: "Action",
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
                    ],
                    destroy: true // Set true untuk memastikan tabel sebelumnya dihancurkan
                });

            },
            error: function(error) {
                console.log(error);
                // Tangani kesalahan jika terjadi
            }
        });
        if (kategori === 'Semua') {
            const desc = $("#desc").html('');
        } else {
            const desc = $("#desc").html(`${kategori}`);
        }
    };

    function deleteDataObat(kode) {
        event.preventDefault()
        $.ajax({
            url: '/apoteker/obat/list/delete/' + kode,
            method: 'GET',
            success: function(response) {
                successAlert('Data Obat Berhasil Dihapus');
                getData('Semua');
            },
            error: function(error) {
                console.log(error);
            },
        });
    }
</script>
