@extends('layouts.main')
@section('content')
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('src/plugins/jquery-asColorPicker/dist/css/asColorPicker.css') }}" /> --}}
    <div class="xs-pd-20-10 pd-ltr-20">
        <div class="title pb-20">
            <h2 class="h3 mb-0">{{ $title }}</h2>
        </div>

        <div class="container-fluid py-3 px-0 ">
            <div class="swiper mySwiper">
                <div class="swiper-wrapper">
                    @foreach ($pasien as $item)
                        <div class="swiper-slide mx-1">
                            <a href="#" onclick="togglemodal(this)" data-toggle="modal" data-target="#create-resep-modal"
                                data-nama="{{ $item->pasien->nama }}" data-gejala="{{ $item->gejala }}"
                                data-kode="{{ $item->kode }}" data-createdat="{{ $item->created_at->format('H:i') }}">

                                <div class="container-slider border rounded-lg px-3 py-2">

                                    <div class="row d-flex align-items-center">

                                        <div class="col-4 slider-number">
                                            <div class="bg-secondary rounded-circle text-white">
                                                <p class="p-3 font-weight-bold font-20">{{ $loop->iteration }}</p>
                                            </div>
                                        </div>

                                        <div class="col-8 text-left">
                                            <p class="font-weight-bold mb-1">
                                                {{ $item->pasien->nama }}
                                            </p>
                                            <p class="">
                                                {{ $item->pasien->umur }} Tahun
                                            </p>
                                        </div>

                                    </div>

                                </div>

                            </a>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>


        <div class="modal fade bs-example-modal" id="create-resep-modal" tabindex="-1" role="dialog"
            aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered rounded">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-6">
                                <span id="modal-nama"></span>
                            </div>
                            <div class="col-6 text-right">
                                <span id="modal-createdat"></span>
                            </div>
                        </div>
                        <div class="font-weight-bold ml-0 font-18">Gejala Pasien</div>
                        <div class="mb-2" id="modal-gejala"></div>
                        <div class="row px-2 mb-2">
                            <div class="font-weight-bold font-18 col-lg-10">Input Resep</div>
                            <button class="btn btn-sm btn-danger col-lg-1" onclick="removeResepRow()"
                                id="deleteRowButton">-</button>
                            <button class="btn btn-sm btn-success col-lg-1" onclick="addResepRows()">+</button>
                        </div>

                        <form action="/dokter/resep/create/new" onsubmit="submitResep(event)" id="formResep" method="POST"
                            class="row p-0 m-0 w-100">
                            @csrf
                            <input type="hidden" name="kode" id="modal-kode">
                            <div class="row my-1 border py-3">
                                <div class="col-lg-4">
                                    <select class="custom-select2 form-control" style="width: 100%;" name="obatId[]">
                                        <option value="">Pilih Obat</option>
                                        @foreach ($obat as $item)
                                            <option value="{{ $item->id }}">{{ $item->namaProduk }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-4 w-100">
                                    <input type="number" name="jumlah[]" placeholder="Jumlah" class="form-control">
                                </div>
                                <div class="col-lg-4">
                                    <select class="custom-select2 form-control" style="width: 100%;" name="satuanObat[]">
                                        <option value="">Pilih Satuan</option>
                                        @foreach ($satuan as $item)
                                            <option value="{{ $item->satuan }}">{{ $item->satuan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-12 my-2">
                                    <input type="text" class="form-control" placeholder="Catatan Obat" name="catatan[]">
                                </div>
                            </div>
                            <div id="resepInputWrapper">

                            </div>
                            <div class="modal-footer m-auto">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">
                                    Batal
                                </button>
                                <button type="submit" class="btn btn-success">
                                    Simpan Resep
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>



        {{-- <div class="modal fade bs-example-modal" id="create-resep-modal" tabindex="-1" role="dialog"
            aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered rounded">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-6">
                                <span id="modal-nama"></span>
                            </div>
                            <div class="col-6 text-right">
                                <span id="modal-createdat"></span>
                            </div>
                        </div>
                        <div class="font-weight-bold ml-0 font-18">Gejala Pasien</div>
                        <div class="mb-2" id="modal-gejala"></div>
                        <div class="row px-2 mb-2">
                            <div class="font-weight-bold font-18 col-lg-10">Input Resep</div>
                            <button class="btn btn-sm btn-danger col-lg-1" onclick="removeResepRow()"
                                id="deleteRowButton">-</button>
                            <button class="btn btn-sm btn-success col-lg-1" onclick="addResepRows()">+</button>
                        </div>

                        <form action="javascript:void(0)" onsubmit="submitResep()" name="createResep" id="formResep"
                            method="POST" class="row p-0 m-0 w-100">
                            @csrf
                            <input type="hidden" name="kode" id="modal-kode">
                            <div class="row my-1">
                                <div class="col-lg-4">
                                    <select class="custom-select2 form-control" style="width: 100%;" id="namaObat">
                                        <option value="">Pilih Obat</option>
                                        @foreach ($obat as $item)
                                            <option value="{{ $item->id }}">{{ $item->namaProduk }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-4 w-100">
                                    <input type="number" id="jumlah" placeholder="Jumlah" class="form-control">
                                </div>
                                <div class="col-lg-4">
                                    <select class="custom-select2 form-control" style="width: 100%;" id="satuanObat">
                                        <option value="">Pilih Satuan</option>
                                        @foreach ($satuan as $item)
                                            <option value="{{ $item->satuan }}">{{ $item->satuan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div id="resepInputWrapper">

                            </div>
                            <div class="font-weight-bold ml-0 mt-2 font-18">Tambah Catatan Disini</div>
                            <textarea name="catatan" style="height: 80px;" placeholder="Masukkan Catatan Disini" class="form-control mb-3"
                                id="catatan" cols="" rows=""></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-success">
                            Simpan Resep <i class="ml-1 icon-copy dw dw-paper-plane1" data-dismiss="modal"
                                id="submit"></i>
                        </button>
                        </form>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
@endsection

<script>
    // function submitResep(event) {
    //     event.preventDefault(); // Menghentikan submit default form

    //     const kode = $('#modal-kode').val();
    //     // ... code to gather other form data ...

    //     // Mengarahkan form action dengan menambahkan kode ke URL
    //     const actionUrl = `/dokter/resep/create/${kode}`;
    //     $('#formResep').attr('action', actionUrl);

    //     // Lakukan submit form
    //     $('#formResep').submit();
    // }

    function togglemodal(elem) {
        var nama = $(elem).data("nama");
        var gejala = $(elem).data("gejala");
        var kode = $(elem).data("kode");
        var createdat = $(elem).data("createdat");

        // console.log(nama, gejala, kode, createdat);

        $("#modal-nama").text(nama);
        $("#modal-gejala").text(gejala);
        $("#modal-kode").val(kode);
        $("#modal-createdat").text(createdat);
        $('.custom-select2').select2({
            dropdownParent: $('#resepInputWrapper')
        });
        $('#deleteRowButton').attr('disabled', 'disabled');
    };


    let resepRows = 0;

    function addResepRows() {
        var newResepRow = createResepRow();
        $('#resepInputWrapper').append(newResepRow);
        $('.custom-select2').select2({
            dropdownParent: $('#resepInputWrapper')
        });
        $('#deleteRowButton').removeAttr('disabled');
        resepRows++;
    };

    function removeResepRow() {
        if (resepRows > 0) {
            $('.row.my-1').last().remove();
            resepRows--;
        }
        if (resepRows === 0) {
            $('#deleteRowButton').attr('disabled', 'disabled');
        }
    };


    function createResepRow() {
        var rowHtml = `
        <div class="row my-1 border-bottom py-3">
            <div class="col-lg-4">
                <select class="custom-select2 form-control" style="width: 100%;" name="obatId[]">
                    <option value="">Pilih Obat</option>
                    @foreach ($obat as $item)
                        <option value="{{ $item->id }}">{{ $item->namaProduk }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-4 w-100">
                <input type="number" name="jumlah[]" placeholder="Jumlah" class="form-control">
            </div>
            <div class="col-lg-4">
                <select class="custom-select2 form-control" style="width: 100%;" name="satuanObat[]">
                    <option value="">Pilih Satuan</option>
                    @foreach ($satuan as $item)
                        <option value="{{ $item->satuan }}">{{ $item->satuan }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-12 my-2">
                <input type="text" class="form-control" placeholder="Catatan Obat" name="catatan[]">
            </div>
        </div>
        `;
        return rowHtml;
    }

    // function submitResep() {
    //     // e.preventDefault();
    //     $(document).ready(function() {

    //         const kode = $('#modal-kode').val();
    //         const catatan = $('#catatan').val();
    //         const csrfToken = $('meta[name="csrf-token"]').attr('content');

    //         const resepItems = []; // Array untuk menyimpan item resep

    //         // Loop melalui setiap baris resep dan mengambil nilai dari masing-masing elemen
    //         $('.row.my-1').each(function() {
    //             const obatId = $('#namaObat').val();
    //             const jumlah = $('#jumlah').val();
    //             const satuanObat = $('#satuanObat').val();

    //             resepItems.push({
    //                 obatId: obatId,
    //                 jumlah: jumlah,
    //                 satuanObat: satuanObat
    //             });

    //         });
    //         const requestData = {
    //             kode: kode,
    //             catatan: catatan,
    //             resepItems: resepItems, // Menyimpan array item resep
    //             isProceed: 1
    //         };


    //         // console.log(requestData);

    //         $.ajax({
    //             url: '/dokter/resep/create/' + kode,
    //             type: 'POST',
    //             headers: {
    //                 'X-CSRF-TOKEN': csrfToken
    //             },
    //             contentType: "application/json", // Tentukan tipe konten yang dikirim
    //             data: JSON.stringify(requestData),
    //             success: function(response) {
    //                 $('#create-resep-modal').modal('hide');
    //                 reload();
    //             },
    //             error: function(xhr, status, error) {
    //                 console.error(error); // Tampilkan kesalahan jika ada
    //             }
    //         });
    //     });
    // };


    function reload() {
        // e.preventDefault();
        $(document).ready(function() {
            $.ajax({
                url: '/dokter/resep/create/getAllResep',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    const resepData = response
                        .data; // asumsi data berada di dalam properti 'data' dari response

                    let swiperHtml =
                        ''; // Inisialisasi string kosong untuk menampung elemen HTML swiper slide

                    resepData.forEach((item, index) => {
                        swiperHtml += `
                        <div class="swiper-slide mx-1">
                                <a href="#" onclick="togglemodal(this)" data-toggle="modal" data-target="#create-resep-modal"
                                    data-nama="${item.pasien.nama}" data-gejala="${item.gejala}"
                                    data-kode="${item.kode}" data-createdat="${item.created_at.format('HH:mm')}">
                                    <div class="container-slider border rounded-lg px-3 py-2">
                                        <div class="row d-flex align-items-center">
                                            <div class="col-4">
                                                <div class="bg-secondary rounded-circle text-white">
                                                    <p class="p-3 font-weight-bold font-20">${index + 1}</p>
                                                </div>
                                            </div>
                                            <div class="col-8 text-left">
                                                <p class="font-weight-bold mb-1">${item.pasien.nama}</p>
                                                <p class="">${item.gejala}</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        `;
                    });
                    $('#sa-success', function() {
                        swal({
                            type: 'success',
                            title: 'Data Berhasil Dikirim',
                            confirmButtonClass: 'btn bg-success',
                        });
                    });

                    // Setelah semua elemen swiper slide dibangun, masukkan ke dalam swiper wrapper
                    $('.swiper-wrapper').html(swiperHtml);

                    // Update swiper hanya setelah elemen diubah

                },
                error: function(xhr, status, error) {
                    console.error(error); // Tampilkan kesalahan jika ada
                }
            });
        });

    }
</script>
@if (session()->has('success'))
    <script>
        reload();
    </script>
@endif
