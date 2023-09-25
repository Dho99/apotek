@extends('layouts.main')
@section('content')
    <div class="xs-pd-20-10 pd-ltr-20">
        <div class="title pb-20 d-flex">
            <h2 class="h3 mb-0">{{ $title }}</h2>
            <button onclick="createPasienModal()" class="btn btn-success ml-auto">
                <i class="icon-copy dw dw-add"></i> Tambah Data
            </button>
        </div>

        {{-- @dd($data) --}}
        <div class="card-box mb-30">
            <div class="pd-20">

                <div class="pb-20">
                    <table class="data-table table nowrap" id="pasienTable">
                        <thead>
                            <tr>
                                <th class="table-plus datatable-nosort">Kode</th>
                                <th>Nama</th>
                                <th>Umur</th>
                                <th>Alamat</th>
                                <th>Email / No Telp</th>
                                <th class="datatable-nosort">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        $().ready(function() {
            refreshTable();
        });

        function createPasienModal() {
            $('#mode').text('Tambah data Pasien');
            $('#showPasienModal').modal('show');
            $('#dataPasienForm input').removeAttr('readonly');
            $('#dataPasienForm input').removeAttr('disabled');
            $('#createBtn').removeClass('d-none');
            $('#emailwrapper').append(`
            <div class="form-group mt-3" id="bornat">
                <label class="font-weight-bold">Tanggal lahir</label>
                <input class="form-control" name="usia" value="" type="date" id="bornAt"
                    required/>
            </div>
            `);
        }

        function getUser(kode, method) {
            if (method === 'edit') {
                $.ajax({
                    url: '/kasir/pasien/get/' + kode,
                    method: 'GET',
                    success: function(response) {
                        const hasil = response.data[0];
                        showModal(hasil);
                    },
                    error: function(error, xhr) {
                        console.error(error);
                        console.log(xhr.responseText);
                    }
                });
            } else {
                const url = '/kasir/pasien/delete/'
                const arg = kode;
                deleteData(url, kode);
            }

        }

        function refreshTable() {
            $.ajax({
                url: '/kasir/pasien/get',
                method: 'GET',
                success: function(response) {
                    updateTable('#pasienTable', response.data, [{
                        data: 'kode'
                    }, {
                        data: 'nama'
                    }, {
                        render: function(data, type, row) {
                            let umur = row.tanggal_lahir;
                            let age = new Date(umur).getFullYear();
                            let usia = new Date().getFullYear() - age;
                            return usia + ' Tahun';
                        }
                    }, {
                        data: 'alamat'
                    }, {
                        data: 'email'
                    }, {
                        title: 'Action',
                        render: function(data, type, row) {
                            return `
                            <a class="btn text-info p-0 mr-2" onclick="getUser('${row.kode}','edit')">
                                <i class="dw dw-eye"></i> View
                            </a>
                            <a class="btn text-danger p-0" onclick="getUser('${row.kode }','delete')"><i class="dw dw-delete-3"></i>
                                Delete</a>
                                `;
                        },
                    }], );
                },

            });
        }

        function updateDataPasien(event) {
            event.preventDefault();
            let kodePasien = 'PSN-' + randomString();
            let usernamePasien = $('#nama').val().toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '');
            let formData = new FormData();
            formData.append('kode', kodePasien);
            formData.append('nama', $('#nama').val());
            formData.append('username', usernamePasien);
            formData.append('email', $('#email').val());
            formData.append('alamat', $('#address').val());
            formData.append('tanggal_lahir', $('#bornAt').val());

            const url = '/kasir/pasien/update/';
            const method = 'POST';
            const dataForm = formData;

            ajaxUpdate(url, method, dataForm);
        }

        function showModal(hasil) {
            $('#mode').text('Data Pasien Kode: ')
            $('.btn.btn-info.ml-auto').removeClass('d-none');
            $('#showPasienModal').modal('show');
            $('#printwrapper').append(`
            <button class="btn btn-secondary noprint" id="printBtn" onclick="printInvoice()"><i class="icon-copy dw dw-print"></i> Print</button>
            `);
            $('#nama').val(hasil.nama);
            $('#email').val(hasil.email);
            let umur = hasil.tanggal_lahir;
            let age = new Date(umur).getFullYear();
            let bornAt = new Date().getFullYear() - age;
            const created_at = new Date(hasil.created_at).toISOString().substring(0, 10);
            $('#address').val(hasil.alamat);
            $('#dataName').text(hasil.kode);
            $('#emailwrapper').append(`
            <div class="form-group mt-3" id="bornatwrapper">
                <label class="font-weight-bold">Tanggal Lahir</label>
                <input class="form-control" name="usia" value="${umur}" type="date" id="bornAt" readonly
                    required readonly/>
            </div>
            <div class="form-group mt-3" id="usia">
                <label class="font-weight-bold">Usia</label>
                <input class="form-control" name="usia" value="${bornAt}" type="text" id="usia" readonly
                    required disabled/>
            </div>
            <div class="form-group mt-3" id="joinat">
                <label class="font-weight-bold">Bergabung Pada</label>
                <input class="form-control" name="usia" value="${created_at}" type="date" id="bornAt" readonly
                    required disabled/>
            </div>
            `)
        }

        function emptyModal() {
            $('#usia, #joinat, #bornatwrapper, #usia, #bornat, #printBtn').remove();
            $('#createBtn, #updateBtn, .btn.btn-info.ml-auto').addClass('d-none');
            $('#dataPasienForm input').val('');
            $('#dataPasienForm input').attr('readonly', 'readonly');
            $('#nama').attr('disabled', 'disabled')
            $('#showPasienModal').modal('hide');
        }
    </script>
@endsection

<div class="modal fade" id="showPasienModal" role="dialog" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content py-4 px-3">
            <div class="row d-flex px-3">
                <h5><span id="mode"></span><span class="font-weight-bold" id="dataName"></span></h5>
                <div id="printwrapper" class="noprint ml-auto">

                </div>
            </div>
            <form action="#" id="dataPasienForm" onsubmit="updateDataPasien(event)">
                <div class="form-group">
                    <label class="font-weight-bold">Nama Lengkap</label>
                    <input class="form-control" name="nama" type="text" value="" id="nama" readonly
                        disabled required />
                </div>
                <div class="form-group" id="emailwrapper">
                    <label class="font-weight-bold">Email / No Telp</label>
                    <input class="form-control" name="nama" type="text" value="" id="email" readonly
                        required />
                </div>
                <div class="form-group">
                    <label class="font-weight-bold">Alamat</label>
                    <input class="form-control" name="alamat" type="text" id="address" value="" required
                        readonly />
                </div>
                <div class="mt-4 mb-2 d-flex">
                    <button class="btn btn-secondary noprint" type="button" onclick="emptyModal()">Kembali</button>
                    <button class="btn btn-info ml-auto d-none noprint" type="button"
                        onclick="changeToEdit('#dataPasienForm')">Edit</button>
                    <button class="btn btn-success ml-auto d-none" id="updateBtn" type="submit">Update</button>
                    <button class="btn btn-success ml-auto d-none" id="createBtn" type="submit">Simpan</button>
                </div>
            </form>
            {{-- </div> --}}
        </div>
    </div>
</div>
