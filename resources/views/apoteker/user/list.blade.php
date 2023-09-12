@extends('layouts.main')
@section('content')
    <div class="xs-pd-20-10 pd-ltr-20">
        <div class="title pb-20">
            <h2 class="h3 mb-0">{{ $title }} <span id="cardTitle">Semua</span></h2>
        </div>

        {{-- @dd($kategoriUser) --}}
        <div class="card-box mb-30">
            <div class="pd-20">
                <div class="row d-flex mb-3">
                    <div class="col-lg-3">
                        <button onclick="createUserModal()" class="btn btn-outline-success w-100">
                            <i class="icon-copy dw dw-add"></i> Tambah Data
                        </button>
                    </div>
                    <div class="col-lg-5"> </div>
                    <div class="col-lg-4">
                        <select name="" id="level" style="width: 100%;" class="custom-select2 form-control"
                            onchange="getUserByLevel()">
                            <option value="">Semua</option>
                            @foreach ($kategoriUser as $key => $item)
                                <option value="{{ $key }}">{{ $item->level }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>


                <div class="pb-20">
                    <table class="table nowrap" id="userTable">
                        <thead>
                            <tr>
                                <th class="table-plus datatable-nosort">Kode</th>
                                <th>Nama</th>
                                <th>Email / No Telp</th>
                                <th>Level</th>
                                <th class="datatable-nosort">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($datas as $item)
                                <tr>
                                    <td>{{ $item->kode }}</td>
                                    <td>{{ $item->nama }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ $item->level }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                                                href="#" role="button" data-toggle="dropdown">
                                                <i class="dw dw-more"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                <a class="dropdown-item" onclick="getUser('{{ $item->kode }}','edit')">
                                                    <i class="dw dw-eye"></i> View
                                                </a>
                                                <a class="dropdown-item text-danger"
                                                    onclick="getUser('{{ $item->kode }}','delete')"><i
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
    </div>

    <div class="modal fade" id="showUserModal" role="dialog" data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content py-4 px-3">
                <div class="container">
                    <h5><span id="mode"></span> Data User <span class="font-weight-bold" id="dataName"></span></h5>
                    <form action="#" id="dataUserForm" onsubmit="updateDataUser(event)">
                        <div class="form-group mt-4">
                            <label class="font-weight-bold d-flex">Kode User <span class="ml-auto d-none">Coba kode
                                    Acak</span></label>
                            <input class="form-control" name="kode" value="" type="text" id="kode" disabled
                                required />
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Username</label>
                            <input class="form-control" name="username" value="" type="text" id="username"
                                required readonly />
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Nama Lengkap</label>
                            <input class="form-control" name="nama" type="text" value="" id="nama" readonly
                                required />
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Email</label>
                            <input class="form-control" name="nama" type="text" value="" id="email" readonly
                                required />
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Tanggal Lahir</label>
                            <input class="form-control" name="usia" value="" type="date" id="bornAt" readonly
                                required />
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Alamat</label>
                            <input class="form-control" name="alamat" type="text" id="address" value="" required
                                readonly />
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Status</label>
                            <select id="status" class="form-control" disabled>
                                <option value="Non Aktif">Nonaktif</option>
                                <option value="Aktif">Aktif</option>
                            </select>
                        </div>
                        <div class="form-group last">
                            <label class="font-weight-bold">Level</label>
                            <select id="uLevel" class="form-control" onchange="randomDokterKode()" disabled>
                                <option value="0">Dokter</option>
                                <option value="1" selected>Apoteker</option>
                                <option value="2">Kasir</option>
                                <option value="3">Pasien</option>
                            </select>
                        </div>

                        <div class="mt-4 mb-2 d-flex">
                            <button class="btn btn-secondary" type="button" onclick="emptyModal()">Kembali</button>
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
            $('#userTable').DataTable({
                responsive: true,
                autoWidth: false,
            });
        });
    </script>
@endsection
<script>
    function randomString() {
        const randomNum = Math.floor(Math.random() * 9999);
        return randomNum;
    }

    function randomDokterKode() {
        let levelInt = $('#uLevel').val();
        const kodeVal = levelInt === '0' ? 'DOK-' : levelInt === '1' ? 'APT-' : levelInt === '2' ? 'KSR-' : 'PSN-';
        let kode = $('#kode').val(kodeVal + randomString());
        if(levelInt === '0'){
            $('.form-group.last').append(`
            <div class="form-group mb-2" id="kategoriSelect">
                <label class="font-weight-bold">Kategori Dokter</label>
                <input class="form-control" name="kategori" type="text" value="" id="kategori" required/>
            </div>
            `);
        }else{
            $('#kategoriSelect').remove();
        }
    }

    function getUser(kode, method) {
        if (method === 'edit') {
            $.ajax({
                url: '/apoteker/user/show/get/' + kode,
                method: 'GET',
                success: function(response) {
                    const hasil = response.data[0];
                    // console.log(response.data[0]);
                    showModal(hasil.username, hasil.kode, hasil.nama, hasil.email, hasil.alamat, hasil
                        .status,
                        hasil.level, hasil.tanggal_lahir, hasil.kategoriDokter, hasil.created_at);
                },
                error: function(error, xhr) {
                    console.error(error);
                    console.log(xhr.responseText);
                }
            });
        } else {
            $.ajax({
                url: '/apoteker/user/delete/' + kode,
                method: 'GET',
                success: function(response) {
                    successAlert(response.message);
                    getUserByLevel();
                },
                error: function(error, xhr) {
                    console.error(error);
                    console.log(xhr.responseText);
                }
            });
        }

    }

    function getUserByLevel() {
        const level = $('#level').val();
        let userTable = $('#userTable').DataTable();
        let url;
        if (level !== '') {
            url = '/apoteker/user/get/' + level;
        } else {
            url = '/apoteker/user/get/all';
        }
        $.ajax({
            url: url,
            method: 'GET',
            success: function(response) {
                if (level === '') {
                    $('#cardTitle').text('Semua');
                } else {
                    $('#cardTitle').text(response.data[0].level);
                }
                userTable.clear().destroy();
                userTable = $('#userTable').DataTable({
                    autoWidth: false,
                    responsive: true,
                    columnDefs: [{
                        targets: "datatable-nosort",
                        orderable: false,
                    }],
                    "lengthMenu": [
                        [10, 25, 50, -1],
                        [10, 25, 50, "All"]
                    ],
                    data: response.data,
                    columns: [{
                        title: 'Kode',
                        data: 'kode'
                    }, {
                        title: 'Nama',
                        data: 'nama'
                    }, {
                        title: 'Email / No Telp',
                        data: 'email'
                    }, {
                        title: 'Level',
                        data: 'level'
                    }, {
                        title: 'Action',
                        render: function(data, type, row) {
                            return `
                                <div class="dropdown">
                                    <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                                        href="#" role="button" data-toggle="dropdown">
                                        <i class="dw dw-more"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                        <a class="dropdown-item" onclick="getUser('${row.kode}','edit')">
                                            <i class="dw dw-eye"></i> View
                                        </a>
                                        <a class="dropdown-item text-danger" onclick="getUser('${row.kode }','delete')"><i class="dw dw-delete-3"></i>
                                            Delete</a>
                                    </div>
                                </div>
                                `
                        }
                    }],
                    destroy: true,
                }, );
            },
            error: function(error, xhr) {
                console.error(error);
                console.log(xhr.responseText);
            }
        });
    }

    function emptyModal() {
        $('#dataUserForm input').val('');
        $('#showUserModal').modal('hide');
        $('#createBtn, #updateBtn, .btn.btn-info.ml-auto').addClass('d-none');
        $('#dataUserForm input').attr('readonly', 'readonly');
        $('#dataUserForm select').attr('disabled', 'disabled');
        $('#joined').remove();
        $('#kategoriSelect').remove();
    }


    function createUserModal() {
        $('#showUserModal').modal('show');
        $('#kode').val('DOK-' + randomString());
        $('#dataUserForm input').removeAttr('readonly disabled');
        $('#dataUserForm select').removeAttr('disabled');
        $('#createBtn').removeClass('d-none');
    }

    function showModal(username, kode, nama, email, alamat, status, level, umur, kategori, joined) {
        const levelOptions = ['Dokter', 'Apoteker', 'Kasir', 'Pasien'];
        const levelForInt = levelOptions.indexOf(level);

        $('#username').val(username);
        $('#kode').val(kode);
        $('#nama').val(nama);
        $('#email').val(email);
        $('#address').val(alamat);
        $(`#status option[value='${status}'`).attr('selected', 'selected');
        $(`#uLevel option[value='${levelForInt}'`).attr('selected', 'selected');

        const ttl = new Date(umur).toISOString().substring(0, 10);
        $('#bornAt').val(ttl);
        const current = new Date().getFullYear();
        const tl = new Date(umur).getFullYear();
        const age = current - tl;

        $('#age').val(age);
        const joinedDate = new Date(joined).toISOString().substring(0, 10);
        $('#joinedAt').val(joinedDate);

        const curr = new Date().toISOString().substring(0, 10);
        $('.form-group.last').after(`
            <div class="form-group" id="joined">
                <label class="font-weight-bold">Tanggal Bergabung</label>
                <input class="form-control" name="usia" value="${joinedDate}" type="date"
                    disabled required />
            </div>
            `);


        if (level === 'Dokter') {
            $('.mt-4.mb-2.d-flex').before(`
                    <div class="form-group mb-2" id="kategoriSelect">
                        <label class="font-weight-bold">Kategori Dokter</label>
                        <input class="form-control" name="kategori" type="text" value="${kategori}" id="kategori" required readonly/>
                    </div>
                `);
        }

        $('#showUserModal').modal('show');
        $('.btn.btn-info.ml-auto').removeClass('d-none');
    }

    function updateDataUser(event) {
        event.preventDefault();
        let formData = new FormData();
        formData.append('kode', $('#kode').val());
        formData.append('nama', $('#nama').val());
        formData.append('username', $('#username').val());
        formData.append('kategori', $('#kategori').val());
        formData.append('email', $('#email').val());
        formData.append('alamat', $('#address').val());
        formData.append('tanggal_lahir', $('#bornAt').val());
        formData.append('level', $('#uLevel').val());

        $.ajax({
            url: '/apoteker/user/update/',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content")
            },
            processData: false,
            contentType: false,
            cache: false,
            data: formData,
            success: function(response) {
                successAlert(response.message);
                $('#showDokter').modal("hide");
                getUserByLevel();
                emptyModal();
            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
                console.log("Response:", xhr.responseText);
            },
        });
    }

    function changeToEdit() {
        $('#dataUserForm input').removeAttr('readonly');
        $('#dataUserForm select').removeAttr('disabled');
        $('#updateBtn').removeClass('d-none');
        $('.btn.btn-info.ml-auto').addClass('d-none');
    }
</script>
