@extends('layouts.main')
@section('content')
    <div class="xs-pd-20-10 pd-ltr-20">
        <div class="title pb-20">
            <h2 class="h3 mb-0">{{ $title }}</h2>
        </div>
        <div class="card-box mb-30 p-3">
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
                            <th>Status</th>
                            <th>Tanggal Kadaluarsa</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                </table>
            </div>
        </div>
    </div>
    {{-- <script>
        $().ready(function() {
            refreshTable();
        });
    </script> --}}
@endsection
<script>
    function refreshTable() {
        const url = '/apoteker/obat/kadaluarsa/filter';
        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                console.log(response.data);
                printable('#myObatTable', response.data, [
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
                        data:null,
                        render: function(data, type, row){
                            return `
                            <small class="badge badge-danger font-weight-normal">Telah Kadaluarsa</small>
                            `;
                        }
                    },
                    {
                        render: function(data, type, row){
                            const date = new Date(row.expDate).toLocaleString();
                            return `<p class="text-danger">${row.expDate}</p>`;
                        }
                    },
                    {
                        render: function(data, type, row){
                                return `
                                <div class="dropdown">
                                    <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                                        href="#" role="button" data-toggle="dropdown">
                                        <i class="dw dw-more"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                        <a class="dropdown-item" onclick="if(confirm('Lanjutkan perbarui data obat ?')) {window.location.href='/apoteker/obat/show/${row.kode}'}">
                                            <i class="icon-copy dw dw-settings2"></i> Perbarui
                                        </a>
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
        // if (kategori === 'Semua') {
        //     const desc = $("#desc").html('');
        // } else {
        //     const desc = $("#desc").html(`${kategori}`);
        // }
    };


    // function deleteDataObat(kode) {
    //     const kodeForDelete = kode;
    //     const url = '/apoteker/obat/list/delete/';
    //     deleteData(url, kodeForDelete);
    // }
</script>
