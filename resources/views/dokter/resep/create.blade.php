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
                        <div class="row px-2 mb-2">
                            <div class="font-weight-bold font-18 col-lg-10">Input Resep</div>
                            <button class="btn btn-sm btn-danger col-lg-1" onclick="removeResepRow()"
                                id="deleteRowButton">-</button>
                            <button class="btn btn-sm btn-success col-lg-1" onclick="addResepRows()">+</button>
                        </div>

                        <form action="javascript:void(0);" onsubmit="submitResep(event)" id="formResep" method="POST"
                            class="row p-0 m-0 w-100">
                            @csrf
                            <input type="hidden" name="kode" id="modal-kode">

                            <div id="resepInputWrapper">

                            </div>
                            <div class="modal-footer m-auto">
                                <button type="button" class="btn btn-secondary" onclick="emptyModal()" aria-hidden="true">
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

    </div>
    <script>
        $().ready(function() {
            reload();
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
                $('.row.my-1').last().remove();
                resepRows--;
                resepRowsArray.pop();
            }
            if (resepRows === 0) {
                $('#deleteRowButton').attr('disabled', 'disabled');
            }
        };

        function createResepRow() {
            var rowHtml = `
                <div class="row my-1 border-bottom py-3">
                    <div class="col-lg-4">
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
                    <div class="col-lg-4">
                        <select class="custom-select2 form-control" style="width: 100%;" id="satuan${resepRows+1}" name="satuanObat[]" >
                            <option value="">Pilih Satuan</option>
                            @foreach ($satuan as $item)
                                <option value="{{ $item->satuan }}">{{ $item->satuan }}</option>
                            @endforeach
                        </select>
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

                    let swiperHtml = '';
                    let i = 1;
                    resepData.forEach((item) => {

                        let created_at = new Date(item.created_at).toLocaleDateString('id-ID');
                        swiperHtml += `
                        <div class="swiper-slide mx-1">
                                <a href="#" onclick="togglemodal(this)" data-toggle="modal" data-target="#create-resep-modal"
                                    data-nama="${item.pasien.nama}" data-gejala="${item.gejala}"
                                    data-kode="${item.kode}" data-createdat="${created_at}">
                                    <div class="container-slider border rounded-lg px-3 py-2">
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
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        };


        function submitResep(event) {
            event.preventDefault();
            let myForm = new FormData();
            myForm.append('kode', $('#modal-kode').val());
            resepRowsArray.forEach((item, index) => {
                let obatId = $(`#obatId${item}`).val();
                let jumlah = $(`#jumlah${item}`).val();
                let satuan = $(`#satuan${item}`).val();
                if (obatId === '' || jumlah === '' || satuan === '') {
                    errorAlert('Tidak boleh kosong');
                } else {
                    myForm.append('obatId[]', $(`#obatId${item}`).val());
                    myForm.append('jumlah[]', $(`#jumlah${item}`).val());
                    myForm.append('satuan[]', $(`#satuan${item}`).val());
                    myForm.append('catatan[]', $(`#catatan${item}`).val());
                    const url = '/dokter/resep/create/new';
                    ajaxUpdate(url, 'POST', myForm);
                }
            });
            setTimeout(() => {
               resepRows = 0;
               resepRowsArray = [];
               reload();
            }, 2000);
        }
        function emptyModal(){
            $('#create-resep-modal').modal('hide');
        }
    </script>
@endsection
