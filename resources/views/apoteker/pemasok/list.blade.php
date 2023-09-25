@extends('layouts.main')
@section('content')
    <link rel="stylesheet" type="text/css" href="{{ asset('src/plugins/jquery-asColorPicker/dist/css/asColorPicker.css') }}" />
    <div class="xs-pd-20-10 pd-ltr-20">
        <div class="title pb-20 d-flex">
            <h2 class="h3 mb-0">{{ $title }}</h2>
            <button onclick="addSupplier()" class="btn btn-success float-right ml-auto"><i
                    class="icon-copy dw dw-add mt-2"></i> Tambah Data</button>
        </div>
        <div class="card-box mb-30">

            <div class="pb-20 pt-3">
                <table class="data-table table nowrap" id="supplierTable">
                    <thead>
                        <tr>
                            <th class="table-plus datatable-nosort">Kode</th>
                            <th>Nama Supplier</th>
                            <th>Alamat</th>
                            <th>Perwakilan Perusahaan</th>
                            <th>No Telp</th>
                            <th class="datatable-nosort">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <div class="modal fade" id="showSupplier" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content p-3">
                <div class="container">
                    <h5><span id="mode"></span> Data Supplier <span class="font-weight-bold" id="nama"></span>
                    </h5>
                    <form action="#" id="dataSupplierForm" onsubmit="updateDataSupplier(event)">
                        <div class="form-group mt-4 mb-2">
                            <label class="font-weight-bold d-flex">Kode Supplier <span class="ml-auto d-none"
                                    onclick="randomSupplierCode()" id="codeGenerator">Coba kode Acak</span></label>
                            <input class="form-control" name="kode" type="text" id="kode" disabled required />
                        </div>
                        <div class="form-group mb-2">
                            <label class="font-weight-bold">Nama Supplier</label>
                            <input class="form-control" name="nama" type="text" id="namaSupplier" required />
                        </div>
                        <div class="form-group mb-2">
                            <label class="font-weight-bold">Alamat</label>
                            <input class="form-control" name="alamat" type="text" id="alamat" readonly required />
                        </div>
                        <div class="form-group mb-2">
                            <label class="font-weight-bold">Perwakilan Perusahaan</label>
                            <input class="form-control" name="perwakilan" type="text" id="perwakilan" required />
                        </div>
                        <div class="form-group mb-2">
                            <label class="font-weight-bold">No Telepon</label>
                            <input class="form-control" name="noTelp" type="text" id="noTelp" required />
                        </div>
                        <div class="mt-4 mb-2 d-flex">
                            <button class="btn btn-secondary" onclick="emptyModal()" data-dismiss="modal">Kembali</button>
                            <button class="btn btn-info ml-auto d-none" type="button"
                                onclick="changeToEdit()">Edit</button>
                            <button class="btn btn-success ml-auto d-none" id="updateBtn" type="submit">Update</button>
                            <button class="btn btn-success ml-auto d-none" id="createBtn" type="submit">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        $().ready(function() {
            refreshTable();
        });
    </script>
@endsection
<style>
    #codeGenerator:hover {
        cursor: pointer;
    }
</style>
<script>
    function addSupplier() {
        showModalSupplier();
        $('#dataSupplierForm input').removeAttr('readonly');
        $('#dataSupplierForm input').removeAttr('disabled');
        $('#createBtn, #codeGenerator').removeClass('d-none');
        $('.btn.btn-info.ml-auto').addClass('d-none');
    }

    function refreshTable() {
        $.ajax({
            url: '/apoteker/pemasok/get',
            method: 'GET',
            success: function(response) {
                updateTable('#supplierTable', response.data, [{
                        title: 'Kode',
                        data: 'kode'
                    },
                    {
                        title: 'Nama Supplier',
                        data: 'nama'
                    },
                    {
                        title: 'Alamat',
                        data: 'alamat'
                    },
                    {
                        title: 'Perwakilan Perusahaan',
                        data: 'perwakilan'
                    },
                    {
                        title: 'No Telp',
                        data: 'noTel'
                    },
                    {
                        title: 'Action',
                        render: function(data, type, row) {
                            return `
                            <div class="dropdown">
                                <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                                    href="#" role="button" data-toggle="dropdown">
                                    <i class="dw dw-more"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                    <a class="dropdown-item" href="#"
                                        onclick="showSupplier('${row.kode}')"><i class="dw dw-eye"></i>
                                        View</a>
                                    <a class="dropdown-item text-danger" onclick="deleteItem('${row.kode}')"
                                        href="#">
                                        <i class="dw dw-delete-3"></i>
                                        Delete
                                    </a>
                                </div>
                            </div>
                            `;
                        },
                    },
                ]);
            },
            error: function(error, xhr) {
                console.error(error);
                console.log(xhr.responseText);
            }
        });
    }

    function randomSupplierCode() {
        $('#kode').val('SPL-' + randomString());
    }

    function emptyModal() {
        $('#dataSupplierForm input').val('');
        $('#kode').attr('disabled', 'disabled');
        $('.btn.btn-info.ml-auto').removeClass('d-none');
        $('#updateBtn, #createBtn, #codeGenerator').addClass('d-none');
        $('#showSupplier').modal('hide');
    }

    function showModalSupplier(kode, nama, alamat, perwakilan, telp) {
        $('#dataSupplierForm input').attr('readonly', 'readonly');
        $('#showSupplier').modal('show');
        let modalTitle = $('#nama');
        if (kode !== undefined) {
            modalTitle.text(kode);
            $('#mode').text('Detail');
        } else {
            modalTitle.text('');
            $('#mode').text('Tambah');
        }
        $('#kode').val(kode);
        $('#namaSupplier').val(nama);
        $('#alamat').val(alamat);
        $('#perwakilan').val(perwakilan);
        $('#noTelp').val(telp);
        $('.btn.btn-info.ml-auto').removeClass('d-none');
    }

    function showSupplier(kode) {
        $.ajax({
            url: '/apoteker/pemasok/get/' + kode,
            method: 'GET',
            success: function(response) {
                const data = response.data[0];
                showModalSupplier(data.kode, data.nama, data.alamat, data.perwakilan, data.noTelp);
            },
            error: function(error, xhr) {
                console.error(error);
                console.log(xhr.responseText);
            }
        });
    }

    function updateDataSupplier(event) {
        event.preventDefault();
        let supplierTable = $('#supplierTable').DataTable();
        const title = $('#mode').text();
        let myForm = new FormData();
        myForm.append('kode', $('#kode').val());
        myForm.append('namaSupplier', $('#namaSupplier').val());
        myForm.append('alamat', $('#alamat').val());
        myForm.append('perwakilan', $('#perwakilan').val());
        myForm.append('noTelp', $('#noTelp').val());

        const url = '/apoteker/pemasok/update';
        const method = 'POST';
        const dataForm = myForm;

        ajaxUpdate(url, method, dataForm)
        // $.ajax({
        //     url: '/apoteker/pemasok/update',
        //     method: 'POST',
        //     headers: {
        //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content")
        //     },
        //     processData: false,
        //     contentType: false,
        //     cache: false,
        //     data: myForm,
        //     success: function(response) {
        //         successAlert(response.message);
        //         console.log(response.data);
        //         refreshTable();
        //         emptyModal();
        //         $('#showSupplier').modal('hide');
        //     },
        //     error: function(error, xhr) {
        //         console.error(error);
        //         console.log(xhr.responseText);
        //     }
        // });
    }

    function deleteItem(kode) {
        $.ajax({
            url: '/apoteker/pemasok/delete/' + kode,
            method: 'GET',
            success: function(response) {
                successAlert(response.message);
                refreshTable();
                emptyModal();
            },
            error: function(error, xhr) {
                console.error(error);
                console.log(xhr.responseText);
            }
        });
    }
</script>
