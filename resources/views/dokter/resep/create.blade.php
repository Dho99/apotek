@extends('layouts.main')
@section('content')
    <div class="xs-pd-20-10 pd-ltr-20">
        <div class="title pb-20">
            <h2 class="h3 mb-0">{{ $title }}</h2>
        </div>

        <div class="container-fluid py-3 px-0 ">
            <div class="swiper mySwiper">
                <div class="swiper-wrapper">


                </div>
            </div>
        </div>


        <div class="modal fade bs-example-modal" id="create-resep-modal" data-backdrop="static" tabindex="-1" role="dialog"
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
                        <div class="row mb-2 d-flex px-0 w-100">
                            <div class="col-10">
                                <div class="font-weight-bold font-18">Input Resep</div>
                            </div>
                            <div class="col-2 d-flex m-auto text-center">
                                <button class="btn btn-sm btn-danger" onclick="removeResepRow()"
                                    id="deleteRowButton">-</button>
                                <button class="btn btn-sm btn-success" onclick="addResepRows()">+</button>
                            </div>
                        </div>

                        {{-- <form action="javascript:void(0);" onsubmit="submitResep(event)" id="formResep" method="POST"
                            class="row p-0 m-0 w-100"> --}}
                            {{-- @csrf --}}
                            <input type="hidden" name="kode" id="modal-kode">

                            <div id="resepInputWrapper">

                            </div>
                            <button class="btn btn-sm btn-outline-success w-100 my-3" id="addNote">
                                Tambahkan Catatan untuk Apoteker
                            </button>
                            <div id="noteWrapper" class="my-3">

                            </div>
                            <div class="m-auto">
                                <button type="button" class="btn btn-secondary" onclick="emptyModal()" aria-hidden="true">
                                    Batal
                                </button>
                                <button class="btn btn-success" onclick="submitResep()">
                                    Simpan Resep
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script>
        // $().ready(function() {
        //     reload();
        // });

        $('#addNote').on('click', function(){
            $('#noteWrapper').append(`
            <textarea id="catatanDokter" placeholder="tambahkan catatan"  class="form-control" cols="30" rows="10"></textarea>
            `);
            $(this).remove();
        });

        function refreshTable(){
            reload();
        }

        function togglemodal(elem) {
            var nama = $(elem).data("nama");
            var gejala = $(elem).data("gejala");
            var kode = $(elem).data("kode");
            var createdat = $(elem).data("createdat");

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
        let resepRowsArray = [];

        function addResepRows() {
            var newResepRow = createResepRow();
            $('#resepInputWrapper').append(newResepRow);
            $('.custom-select2').select2({
                dropdownParent: $('#resepInputWrapper')
            });
            $('#deleteRowButton').removeAttr('disabled');
            resepRows += 1;
            resepRowsArray.push(resepRows);
        };

        function removeResepRow() {
            if (resepRows > 0) {
                $('.row.py-2.border-bottom').last().remove();
                $('.p-0.m-0.font-weight-bold').last().remove();
                resepRows--;
                resepRowsArray.pop();
            }
            if (resepRows === 0) {
                $('#deleteRowButton').attr('disabled', 'disabled');
            }
        };

        function createResepRow() {
            var rowHtml = `
            <p class="p-0 m-0 font-weight-bold">Record ${resepRows+1}</p>
                <div class="row py-2 border-bottom">
                    <div class="col-lg-8">
                        <select class="custom-select2 form-control" style="width: 100%;" id="obatId${resepRows+1}" >
                            <option value="">Pilih Obat</option>
                            @foreach ($obat as $item)
                                <option value="{{ $item->id }}">{{ $item->namaProduk }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4 w-100">
                        <input type="number" name="jumlah[]" placeholder="Jumlah" id="jumlah${resepRows+1}"  class="form-control" >
                    </div>
                    <div class="col-lg-12 my-2">
                        <input type="text"  class="form-control" placeholder="Catatan Obat" id="catatan${resepRows+1}" name="catatan[]">
                    </div>
                </div>
                `;
            return rowHtml;
        }

        function reload() {
            $.ajax({
                url: '/dokter/resep/create/getAllResep',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    const resepData = response.data;
                    if(resepData.length == 0){
                        $('.swiper.mySwiper').append(`
                            <div class="col-lg-12 col-md-12 col-sm-12 mb-30">
                                <div class="alert alert-warning w-100" role="alert">
                                    Data permintaan Resep sedang kosong atau tidak ditemukan
                                </div>
                            </div>
                        `);
                    }else{
                        let swiperHtml = '';
                        let i = 1;
                        resepData.forEach((item) => {
                            let created_at = new Date(item.created_at).toLocaleDateString('id-ID');
                            swiperHtml += `
                            <div class="swiper-slide p-2">
                                    <a href="#" onclick="togglemodal(this)" data-toggle="modal" data-target="#create-resep-modal"
                                        data-nama="${item.pasien.nama}" data-gejala="${item.gejala}"
                                        data-kode="${item.kode}" data-createdat="${created_at}">
                                        <div class="container-slider border rounded-lg px-3 py-2 shadow">
                                            <div class="row d-flex align-items-center">
                                                <div class="col-4">
                                                    <div class="bg-secondary rounded-circle text-white">
                                                        <p class="p-3 font-weight-bold font-20">${i++}</p>
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
                        $('.swiper-wrapper').html(swiperHtml);
                    }

                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        };

        function submitResep() {
            let obatId = $(`#obatId${resepRows}`).val();
            let jumlah = $(`#jumlah${resepRows}`).val();
            if (obatId === '' || jumlah === '' || jumlah == 0) {
                errorAlert('Tidak boleh kosong');
            } else {
                let myForm = new FormData();
                    resepRowsArray.forEach((item, index) => {
                        myForm.append('kode', $('#modal-kode').val());
                        myForm.append('obatId[]', $(`#obatId${item}`).val());
                        myForm.append('jumlah[]', $(`#jumlah${item}`).val());
                        myForm.append('catatan[]', $(`#catatan${item}`).val());
                        myForm.append('catatanDokter', $('#catatanDokter').val());
                    });
                    const url = '/dokter/resep/create/new';
                    ajaxUpdate(url, 'POST', myForm);
                    setTimeout(() => {
                       resepRows = 0;
                       resepRowsArray = [];
                        location.reload();
                    }, 1500);
                }
        }

        function emptyModal(){
            $('#create-resep-modal').modal('hide');
        }
    </script>
@endsection
