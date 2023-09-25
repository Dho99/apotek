@extends('layouts.main')
@section('content')
    <div class="xs-pd-20-10 pd-ltr-20">
        <div class="title pb-20 d-flex">
            <h2 class="h3 mb-0">{{ $title }}</h2>
            <button type="button" onclick="addNewCategory()" class="btn btn-success ml-auto"
            style="font-size: 15px;"><i class="icon-copy dw dw-add"></i> Tambah Kategori</button>
        </div>
        <div class="card-box mb-30 pt-3">
            <div class="pb-20">
                <table class="data-table table nowrap" id="myKategoriTable">
                    <thead>
                        <tr>
                            <th class="table-plus datatable-nosort">No</th>
                            <th>Kategori</th>
                            <th>Keterangan</th>
                            {{-- <th>Jumlah Produk</th> --}}
                            <th class="datatable-nosort">Action</th>
                        </tr>
                    </thead>

                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addKategoriModal" role="dialog" data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body font-18">
                    <div class="modal-title text-center font-weight-bold font-24">
                        <div id="title"></div>
                    </div>
                    <form action="#" id="dataKategoriForm" onsubmit="updateKategori(event)">
                        <div class="form-group mt-4">
                            <label class="font-weight-bold d-flex">Kategori</label>
                            <input class="form-control" name="" value="" type="text" id="kategori" readonly
                                required />
                        </div>
                        <div class="form-group mb-4">
                            <label class="font-weight-bold">Keterangan</label>
                            <input class="form-control" name="" value="" type="text" id="keterangan"
                                readonly required />
                        </div>
                        <div class="form-group">
                            <button type="button" onclick="emptyModal()" class="btn btn-secondary">Tutup</button>
                            <button type="submit" onclick="changeToEdit('#addKategoriForm')" id="editBtn"
                                class="btn btn-info d-none">Edit</button>
                            <button type="submit" id="updateBtn" class="btn btn-success d-none float-right">Simpan</button>
                        </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $().ready(function() {
            refreshTable();
        });

        function addNewCategory() {
            $('#title').text('Tambah Kategori');
            $('#addKategoriModal').modal('show');
            $('#dataKategoriForm input').removeAttr('readonly');
            $('#updateBtn').removeClass('d-none');
        }

        function emptyModal() {
            $('#dataKategoriForm input').attr('disabled');
            $('#dataKategoriForm input').val('');
            $('#editBtn #createBtn').addClass('d-none');
            $('#addKategoriModal').modal('hide');
        }

        function updateKategori(e) {
            e.preventDefault();
            let formData = new FormData();
            formData.append('kategori', $('#kategori').val());
            formData.append('keterangan', $('#keterangan').val());
            const url = '/obat/kategori/update';
            ajaxUpdate(url, 'POST', formData);
            setTimeout(() => {
                emptyModal();
            }, 2000);
        }

        function refreshTable() {
            let i = 1;
            const url = '/obat/kategori/all';

            $.ajax({
                url: url,
                method: 'GET',
                success: function(response) {
                    updateTable('#myKategoriTable', response.data, [{
                        render: function(data, type, row) {
                            return i++;
                        }
                    }, {
                        data: 'golongan'
                    }, {
                        data: 'keterangan'
                    }, {
                        render: function(data, type, row) {
                            return `
                                <a class="text-danger" onclick="deleteKategori('${row.golongan }')"><i class="dw dw-delete-3"></i>
                                    Delete</a>
                                `;
                        }
                    }, ]);
                },
                error: function(error, xhr) {
                    console.log(error);
                    console.log(xhr.responseText);
                },
            });
        }

        function deleteKategori(golongan) {
            const url = '/obat/kategori/delete/';
            deleteData(url, golongan);
        }
    </script>
@endsection

