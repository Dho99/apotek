@extends('layouts.main')
@section('content')
    <div class="xs-pd-20-10 pd-ltr-20">
        <div class="title pb-20 d-flex">
            <h2 class="h3 mb-0">{{ $title }}</h2>
            <button class="btn btn-success ml-auto" onclick="tambahLaporan()">
                <span class="icon-copy dw dw-add"></span> Tambah Data
            </button>
        </div>

        {{-- @dd($keterangan) --}}
        <div class="card-box mb-20">
            <div class="pd-20">
                <div class="row d-flex px-3">
                    <div class="font-20 px-3">Total Saldo</div>
                    <div class="font-24 font-weight-bold" id="saldoText"></div>
                </div>
            </div>
        </div>
        <div class="card-box mb-30">
            <div class="pd-20">
                <div class="row d-flex mb-3">
                    <div class="col-lg-8">
                        Filter data berdasarkan <span id="kategoriTitle"></span>
                    </div>
                    <div class="col-lg-4">
                        <select name="" id="categoryKeuangan" style="width: 100%;"
                            class="custom-select2 form-control" onchange="refreshTable()">
                            <option value="" selected>Semua</option>
                            @foreach ($kategori as $item)
                                <option value="{{ $item }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="pb-20">
                    <table class="data-table table nowrap" id="myKeuanganTable">
                        <thead>
                            <tr>
                                <th class="table-plus datatable-nosort">No</th>
                                <th>Tanggal</th>
                                <th>Transaksi oleh</th>
                                <th>Keterangan</th>
                                <th>Kategori</th>
                                <th>Jumlah</th>
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
    <div class="modal fade" id="tambahLaporanModal" data-backdrop="static" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content shadow">
                <div class="modal-body">
                    <div class="modal-title text-center font-weight-bold font-20" id="title"></div>
                    <form action="" onsubmit="createLaporan(event)" id="myLaporanForm">
                        <div class="form-group mt-4" id="last">
                            <label class="font-weight-bold d-flex">Keterangan</label>
                            <input class="form-control" value="" type="text" id="keterangan" disabled required />
                        </div>
                        <div class="mt-4 mb-2 d-flex">
                            <button class="btn btn-secondary" type="button" onclick="emptyModal()">Kembali</button>
                            <button class="btn btn-info ml-auto d-none" type="button"
                                onclick="changeToEdit('#dataUserForm')">Edit</button>
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
    <script>
        function changeKategori() {
            let kategoriVal = $('#kategoriChanger').val();
            if (kategoriVal === 'Debit') {
                $('#jumlahLabel').remove();
                $('#kategoriwrapper').after().append(`
                <div class="form-group mt-4" id="jumlahLabel">
                    <label class="font-weight-bold d-flex">Debit</label>
                    <input class="form-control" name="kode" value="" type="text" id="jumlah"
                        required />
                </div>
                `);
            } else if (kategoriVal === 'Kredit') {
                $('#jumlahLabel').remove();
                $('#kategoriwrapper').after().append(`
                <div class="form-group mt-4" id="jumlahLabel">
                    <label class="font-weight-bold d-flex">Kredit</label>
                    <input class="form-control" name="kode" value="" type="text" id="jumlah"
                        required />
                </div>
                `);
            } else {
                $('#jumlahLabel').remove();
            }
            inputMaskFormat('#jumlah');
        }

        let kodeLaporan;

        function refreshTable() {
            const kategori = $('#categoryKeuangan').val();
            const kode = kodeLaporan;
            let i = 1;
            let text;
            if (kategori === '') {
                text = 'Semua';
            } else {
                text = kategori;
            }
            $('#kategoriTitle').text(text);
            $.ajax({
                url: '/apoteker/laporan/keuangan/get',
                method: 'GET',
                data: {
                    kategori: kategori,
                    kode: kode
                },
                success: function(response) {
                    let nf = new Intl.NumberFormat('id-ID', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                    $('#saldoText').text('Rp '+nf.format(response.saldo));
                    if (response.modal === 'true') {
                        const hasil = response.data[0];
                        showModal(hasil);
                    } else {
                        updateTable('#myKeuanganTable', response.data,
                            [{
                                render: function(data, type, row) {
                                    return i++;
                                },
                            }, {
                                render: function(data, type, row) {
                                    return moment(`${row.created_at}`).format('DD/MM/YYYY');
                                }
                            }, {
                                data: 'user.nama'
                            }, {
                                data: 'keterangan'
                            }, {
                                data: 'kategori'
                            }, {
                                render: function(data, type, row, meta) {
                                    return 'Rp. ' + $.fn.dataTable.render.number(',', '.', 0,
                                        '').display(`${row.jumlah}`);
                                    return jumlah;
                                }
                            }, {
                                render: function(data, type, row) {
                                    return `
                                    <a class="text-primary mr-3 btn p-0" onclick="getDataKeuangan('${row.id}')">
                                        <i class="dw dw-eye"></i> Detail
                                    </a>
                                    `;
                                }
                            }]
                        );
                    }
                },
                error: function(error, xhr) {
                    console.log(error);
                    console.log(xhr.responseText);
                }
            });
        }

        function getDataKeuangan(id) {
            kodeLaporan = id;
            refreshTable();
        }

        function tambahLaporan() {
            $('#title').text('Tambah Data Laporan');
            $('#tambahLaporanModal').modal('show');
            $('#myLaporanForm input').removeAttr('disabled');
            $('#createdAtrx, #tanggalTransaksi').remove();
            $('#kategoriwrapper, #createBtn').removeClass('d-none');
            $('#last').append(`
            <div class="form-group mt-4" id="kategoriwrapper">
                <label class="font-weight-bold d-flex">Kategori</label>
                <select name="" class="form-control" id="kategoriChanger" onchange="changeKategori()"
                    required>
                    <option value="">Pilih Kategori</option>
                    <option value="Kredit">Kredit</option>
                    <option value="Debit">Debit</option>
                </select>
            </div>
            `);

        }

        function showModal(hasil) {
            $('#saldowrapper').removeClass('d-none');
            $('#tambahLaporanModal').modal('show');
            $('#title').text('Detail Laporan');
            const createdAt = new Date(hasil.created_at).toISOString().substring(0, 10);
            $('#keterangan').val(hasil.keterangan);
            $('#last').append(`
            <div class="form-group mt-4" id="createdAtrx">
                <label class="font-weight-bold d-flex">Tanggal Transaksi</label>
                <input class="form-control" value="${createdAt}" type="date" id="tanggalTransaksi"
                    disabled required/>
            </div>
            `);
            if (hasil.kategori === 'Debit') {
                $('#last').after().append(`
                <div class="form-group mt-4" id="jumlahLabel">
                    <label class="font-weight-bold d-flex">Debit</label>
                    <input class="form-control debit" name="kode" value="${hasil.jumlah}" type="text" id="jumlah" disabled
                        required />
                </div>
                `);
            } else {
                $('#last').after().append(`
                <div class="form-group mt-4" id="jumlahLabel">
                    <label class="font-weight-bold d-flex">Kredit</label>
                    <input class="form-control kredit" name="kode" value="${hasil.jumlah}" type="text" id="jumlah" disabled
                        required />
                </div>
                `);
            }
            inputMaskFormat('#jumlah');
        }

        function emptyModal() {
            $('#myLaporanForm input').val('');
            $('#myLaporanForm input').attr('disabled', 'disabled');
            $('#saldowrapper, #createdAtrx, #tanggalTransaksi, #kategoriwrapper, #jumlahLabel').remove();
            $('#tambahLaporanModal').modal('hide');
        }

        function createLaporan(event) {
            event.preventDefault();
            let url = '/apoteker/laporan/keuangan/create';
            let formData = new FormData();
            formData.append('keterangan', $('#keterangan').val());
            formData.append('kategori', $('#kategoriChanger').val());
            formData.append('jumlah', $('#jumlah').cleanVal());

            ajaxUpdate(url, 'POST', formData);
        }
    </script>
@endsection
