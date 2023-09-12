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
                        <select id="filterDokter" class="form-control rounded-right custom-select2">
                            <option value="">Semua</option>
                            @foreach ($kategoriDokter as $item)
                                <option value="{{ $item->kategoriDokter }}">{{ $item->kategoriDokter }}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-info rounded-left" onclick="filterKategoriDokter()">Cari</button>
                    </div>
                    <div class="col-xl-3 col-lg-12 col-md-12 col-sm-12 ml-auto">
                        <a href="#" onclick="createDokterModal()" class="btn btn-outline-success w-100">
                            <span class="icon-copy dw dw-add"></span>
                            Tambah Data
                        </a>
                    </div>
                </div>
            </div>
            <div class="pb-20">
                <table class="data-table table nowrap" id="dokterTable">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama Dokter</th>
                            <th>Kategori Dokter</th>
                            <th class="datatable-nosort">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dokter as $item)
                            <tr>
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
                                            <a class="dropdown-item" href="#"
                                                onclick="editDokterModal('{{ $item->kode }}')"><i class="dw dw-eye"></i>
                                                View</a>

                                            <a class="dropdown-item text-danger"
                                                onclick="deleteDokter('{{ $item->kode }}')"><i class="dw dw-delete-3"></i>
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

    <div class="modal fade" id="showDokter" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content py-4 px-3">
                <div class="container">
                    <h5><span id="mode"></span> Data Dokter <span class="font-weight-bold" id="dataName"></span></h5>
                    <form action="#" id="dataDokterForm" onsubmit="updateDataDokter(event)">
                        <div class="form-group mt-4 mb-2">
                            <label class="font-weight-bold d-flex">Kode Dokter <span class="ml-auto d-none" onclick="randomDokterKode()" id="codeGenerator">Coba kode Acak</span></label>
                            <input class="form-control" name="kode" type="text" id="kode" disabled required/>
                        </div>
                        <div class="form-group mb-2">
                            <label class="font-weight-bold">Username Dokter</label>
                            <input class="form-control" name="username" type="text" id="username" required />
                        </div>
                        <div class="form-group mb-2">
                            <label class="font-weight-bold">Nama Dokter</label>
                            <input class="form-control" name="nama" type="text" id="nama" readonly required />
                        </div>
                        <div class="form-group mb-2">
                            <label class="font-weight-bold">Kategori Dokter</label>
                            <input class="form-control" name="kategori" type="text" id="kategori" required/>
                        </div>
                        <div class="form-group mb-2">
                            <label class="font-weight-bold">Tanggal Bergabung</label>
                            <input class="form-control" name="dateOfJoin" type="text" id="dateOfJoin" disabled required />
                        </div>
                        <div class="form-group mb-2">
                            <label class="font-weight-bold">Alamat</label>
                            <input class="form-control" name="alamat" type="text" id="address" required/>
                        </div>
                        <div class="mt-4 mb-2 d-flex">
                            <button class="btn btn-secondary" onclick="emptyModal()" data-dismiss="modal">Kembali</button>
                            <button class="btn btn-info ml-auto d-none" type="button" onclick="changeToEdit()">Edit</button>
                            <button class="btn btn-success ml-auto d-none" id="updateBtn" type="submit">Update</button>
                            <button class="btn btn-success ml-auto d-none" id="createBtn" type="submit">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
<style>
    span.ml-auto{
        font-weight: 200;
    }
    span.ml-auto:hover{
        cursor: pointer;
        font-weight: 200;
    }
</style>
<script>
    function randomDokterKode(){
        const randomString = Math.floor(Math.random() * 9999);
        $('#kode').val('DOK-'+randomString);
    }

    function emptyModal(){
        $('#dataDokterForm input').val('');
        $('.btn.btn-info.ml-auto, #updateBtn, #createBtn, #codeGenerator').addClass('d-none');
    }
    function createDokterModal(){
        $('#showDokter').modal('show');
        $('#dataDokterForm input').removeAttr('disabled');
        $('#dataDokterForm input').removeAttr('readonly');
        $('#createBtn , #codeGenerator').removeClass('d-none');

    }

    function filterKategoriDokter(){
        const kategori = $('#filterDokter').val();
        refreshTable(kategori);
    }

    function refreshTable(kategori) {
        let dokterTable = $('#dokterTable').DataTable();
        let url;
        if(kategori !== undefined){
            url = '/apoteker/dokter/get/'+kategori;
        }else{
            url = '/apoteker/dokter/get/';
        }
        $.ajax({
            url: url,
            method: 'GET',
            success: function(response) {
                emptyModal();
                dokterTable.clear().destroy();
                dokterTable = $('#dokterTable').DataTable({
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
                            title: "Nama Dokter",
                            data: "nama"
                        },
                        {
                            title: "Kategori Dokter",
                            data: "kategoriDokter"
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
                                            <a class="dropdown-item" href="#"
                                                onclick="editDokterModal('${row.kode}')"><i class="dw dw-eye"></i>
                                                View</a>
                                            <a class="dropdown-item text-danger" onclick="deleteDokter('${row.kode}')"><i
                                                    class="dw dw-delete-3"></i>
                                                Delete</a>
                                        </div>
                                    </div>
                                `;
                            }
                        }
                    ],
                    destroy: true, // Set true untuk memastikan tabel sebelumnya dihancurkan
                });
            },
            error: function(error, xhr) {
                console.log(error.message);
                console.log(xhr.responseText);
            }
        });
    }

    function editDokterModal(kode) {
        $.ajax({
            url: '/apoteker/dokter/' + kode,
            method: 'GET',
            success: function(response) {
                const data = response.data[0];
                showModal(data.kode, data.username, data.nama, data.kategoriDokter, data.created_at, data
                    .alamat, data.username);
            },
            error: function(error, xhr) {
                console.log(xhr.responseText);
                console.log(error.message);
            }
        });
    }

    function showModal(kode, username, nama, kategori, dateOfJoin, address) {
        const dateFormatted = new Date(dateOfJoin);
        const date = dateFormatted.getDate();
        const month = dateFormatted.getMonth();
        const year = dateFormatted.getFullYear();
        $('#mode').text('Detail');
        $('#dataName').text(nama);
        $('#kode').val(kode);
        $('#username').val(username);
        $('#nama').val(nama);
        $('#kategori').val(kategori);
        $('#address').val(address);
        $('#dateOfJoin').val(date + '-' + month + '-' + year);
        $('#showDokter').modal("show");
        $('#dataDokterForm input').attr('readonly', 'readonly');
        $('#updateBtn').addClass('d-none');
        $('.btn.btn-info.ml-auto').removeClass('d-none');
    }

    function changeToEdit(method) {
        $('#dataDokterForm input').removeAttr('readonly');
        $('.btn.btn-info.ml-auto, #createBtn').addClass('d-none');
        $('#updateBtn').removeClass('d-none');
        $('#mode').text('Update');
    }

    function updateDataDokter(event) {
        event.preventDefault();
        let formData = new FormData();
        formData.append('kode', $('#kode').val());
        formData.append('nama', $('#nama').val());
        formData.append('username', $('#username').val());
        formData.append('kategori', $('#kategori').val());
        formData.append('alamat', $('#address').val());

        const nama = formData.get('nama');
        const kode = $('#kode').val();
        $.ajax({
            url: '/apoteker/dokter/update/'+kode,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content")
            },
            processData: false,
            contentType: false,
            cache: false,
            data: formData,
            success: function(response) {
                console.log(response.data);
                successAlert(`Data Dokter ${nama} berhasil Di Update`);
                $('#showDokter').modal("hide");
                refreshTable();
                emptyModal();
            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
                console.log("Response:", xhr.responseText);
            },
        });
    }

    function deleteDokter(kode) {
        $.ajax({
            url: '/apoteker/dokter/delete/' + kode,
            method: 'GET',
            success: function(response) {
                successAlert(response.message);
                refreshTable();
            },
            error: function(error, xhr) {
                console.log(error.message);
                console.log(xhr.responseText);
            }
        })
    }
</script>
