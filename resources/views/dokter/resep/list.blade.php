@extends('layouts.main')
@section('content')
    <div class="xs-pd-20-10 pd-ltr-20">
        <div class="title pb-10 d-flex">
            <h2 class="h3 mb-0">{{ $title }}</h2>
            <div class="card-box ml-auto px-4 d-flex align-items-center">
                <i class="icon-copy dw dw-search font-24"></i>
                <input type="text" name="" id="filterInput" oninput="filterContainer()" class="form-control border-0"
                    placeholder="Cari disini">
            </div>
        </div>

        <div class="card-box mb-30">
            {{-- @dd($data) --}}
            <div class="row p-5">
                @forelse ($data as $item)
                <div class="col-xl-12 text-center pb-2">
                        <div class="bg-lightgreen rounded d-flex align-items-center row py-3 px-1">
                            <div class="col-xl-1 col-md-3">
                                {{ ($data->currentpage() - 1) * $data->perpage() + $loop->index + 1 }}
                            </div>
                            <div class="col-xl-2 col-md-3">
                                {{ $item->created_at->format('d-m-Y') }}
                            </div>
                            <div class="col-xl-2 col-md-3">
                                {{ $item->created_at->format('H:i') }}
                            </div>
                            <div class="col-xl-4 col-md-3">
                                {{ $item->pasien->nama }}
                            </div>
                            <div class="col-xl-3 col-md-12 py-2">
                                @if(!empty($item->alasanPenolakan))
                                <a href="#" class="btn btn-warning text-light w-100 p-2" type="button" data-toggle="modal"
                                data-target="#detail-reject-{{ $item->kode }}">
                                    Resep Ditolak
                                </a>
                                @else
                                <a href="#" class="btn btn-success w-100 p-2" data-toggle="modal"
                                data-target="#detail-resep-modal-{{ $item->kode }}" type="button">
                                Lihat Resep
                                </a>
                                @endif
                            </div>

                    <div class="modal fade bs-example-modal" id="detail-resep-modal-{{ $item->kode }}" tabindex="-1"
                        role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal modal-dialog-centered">
                            <div class="modal-content text-left">
                                <div class="modal-header">
                                    <div class="modal-title  font-18" id="myLargeModalLabel">
                                        Detail Resep {{ $item->kode }}
                                    </div>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                        ×
                                    </button>
                                </div>
                                <div class="modal-body pb-5">
                                    <div class="border-bottom py-2 px-5 row">
                                        <div class="col-xl-6">
                                            <p class="font-24 mb-1">{{ $item->dokter->nama }}</p>
                                            <p>Dokter {{ $item->dokter->kategoriDokter }}</p>
                                        </div>
                                        <div class="col-xl-6 text-right">
                                            <p>Dibuat {{$item->created_at->diffForHumans()}} Pada {{ $item->created_at->format('H:i') }}</p>
                                        </div>
                                    </div>
                                    <div class="border-bottom pt-3 px-5 row">
                                        <div class="col-xl-6">
                                            <p>{{ $item->pasien->nama }}</p>
                                            <p>{{ $item->gejala }}</p>
                                        </div>
                                        @php
                                            $now = \Illuminate\Support\Carbon::now()->format('Y');
                                            $age = $now - \Illuminate\Support\Carbon::parse($item->pasien->tanggal_lahir)->format('Y')
                                        @endphp
                                        <div class="col-xl-6 text-right">
                                            <p>{{ $age }} Tahun</p>
                                        </div>
                                    </div>
                                        @php
                                            $obat = \App\Models\Produk::whereIn('id', json_decode($item->obat_id))->get();
                                        @endphp
                                        @foreach ($obat as $index => $dataresep)
                                        <div class="pt-3 px-5 row border-bottom">
                                            <div class="col-xl-6">
                                                    <p class="font-18 mb-1">{{ $dataresep->namaProduk }}</p>
                                                    <p>{{ json_decode($item->catatan)[$index] }}</p>
                                                </div>
                                                <div class="col-xl-6 text-right">
                                                    <p>{{ json_decode($item->jumlah)[$index] }} {{ $dataresep->satuan }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade bs-example-modal" id="detail-reject-{{ $item->kode }}" tabindex="-1"
                        role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal modal-dialog-centered">
                            <div class="modal-content text-left">
                                <div class="modal-header">
                                    <div class="modal-title font-18" id="myLargeModalLabel">
                                        Detail Penolakan Resep {{ $item->kode }}
                                    </div>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                        ×
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="border-bottom py-2 px-5 row">
                                        <div class="col-xl-6">
                                            <p class="font-24 mb-1">{{ $item->apoteker->nama }}</p>
                                            <p>Penolakan Resep</p>
                                        </div>
                                        <div class="col-xl-6 text-right">
                                            <p>Ditolak Pada <br> {{ $item->updated_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    <div class="border-bottom pt-3 px-5 row">
                                        <div class="col-xl-6">
                                            <p><span class="font-weight-bold">Pesan dari Apoteker</span><br>{{ $item->alasanPenolakan }}</p>
                                        </div>
                                    </div>
                                    <div class="border-bottom pt-3 px-5 row">
                                        <div class="col-xl-6">
                                            <p>{{ $item->pasien->nama }}</p>
                                            <p>{{ $item->gejala }}</p>
                                        </div>
                                        @php
                                            $now = \Illuminate\Support\Carbon::now()->format('Y');
                                            $age = $now - \Illuminate\Support\Carbon::parse($item->pasien->tanggal_lahir)->format('Y')
                                        @endphp
                                        <div class="col-xl-6 text-right">
                                            <p>{{ $age }} Tahun</p>
                                        </div>
                                    </div>
                                    <div class="pt-3 px-5 row">
                                        @php
                                            $obat = \App\Models\Produk::whereIn('id', json_decode($item->obat_id))->get();
                                            @endphp
                                        @foreach ($obat as $index => $dataresep)
                                            <div class="col-xl-6">
                                                    <p class="font-18 mb-1">{{ $dataresep->namaProduk }}</p>
                                                    <p>{{ json_decode($item->catatan)[$index] }}</p>
                                                </div>
                                                <div class="col-xl-6 text-right">
                                                    <p>{{ json_decode($item->jumlah)[$index] }} {{ $dataresep->satuan }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="pb-3 px-5 row d-flex">
                                        <button class="btn btn-outline-secondary" data-dismiss="modal">
                                            Tutup
                                        </button>
                                        <button class="btn btn-success float-right ml-auto" onclick="updateResepModal('{{$item->kode}}')">
                                            Perbarui Resep
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-lg-12 bg-warning text-center font-weight-bold font-20 text-light py-2">
                        No Data Found
                    </div>
                    @endforelse

            {{ $data->links() }}
        </div>
    </div>


    <div class="modal fade bs-example-modal" id="update-resep" data-backdrop="static" tabindex="-1"
        role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-left">
                <div class="modal-header">
                    <div class="modal-title font-18" id="myLargeModalLabel">
                        Perbarui Resep <span id="kodeResep"></span>
                    </div>
                    <button type="button" class="close" aria-hidden="true" onclick="hideModal()">
                        ×
                    </button>
                </div>
                <div class="modal-body px-4">
                    <div class="row py-2" id="infoResep">

                    </div>
                    <div class="row mb-2">
                        <div class="font-weight-bold font-18 col-lg-10">Update Resep</div>
                        <button class="btn btn-sm btn-danger col-lg-1" onclick="removeResepRow()"
                            id="deleteRowButton">-</button>
                        <button class="btn btn-sm btn-success col-lg-1" onclick="addResepRows()">+</button>
                    </div>
                    <div id="resepInputWrapper">

                    </div>
                    <div class="row py-2 d-flex">
                        <button class="btn btn-outline-secondary" onclick="hideModal()">
                            Tutup
                        </button>
                        <button onclick="submitResep()" class="btn btn-success ml-auto">
                            Perbarui Resep
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>

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
                    <div class="col-lg-8">
                        <select class="custom-select2 form-control" style="width: 100%;" id="obatId${resepRows+1}" required>
                            <option value="">Pilih Obat</option>
                            @php
                                $obat = \App\Models\Produk::all();
                            @endphp
                            @foreach ($obat as $item)
                                <option value="{{ $item->id }}">{{ $item->namaProduk }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4 w-100">
                        <input type="number" name="jumlah[]" placeholder="Jumlah" id="jumlah${resepRows+1}"  class="form-control" required>
                    </div>
                    <div class="col-lg-12 my-2">
                        <input type="text"  class="form-control" placeholder="Catatan Obat" id="catatan${resepRows+1}" name="catatan[]" required>
                    </div>
                </div>
                `;
            return rowHtml;
        }

        function updateResepModal(kode){
            $(`#detail-reject-${kode}`).modal('hide');
            $('#update-resep').modal('show');
            $('#kodeResep').text(kode);
            $('#deleteRowButton').attr('disabled', 'disabled');
            $.ajax({
                url: '/resep/get/'+kode,
                method: 'GET',
                success: function(response){
                    let hasil = response.data[0];
                    $('#infoResep').append(`
                    <div class="col-12">
                            <p>Nama Pasien <br>
                                <span id="namaPasien">${hasil.pasien.nama}</span>
                            </p>
                            <p>Gejala <br>
                                <span id="gejala">${hasil.gejala}</span>
                            </p>
                        </div>
                    `);
                },
                error: function(xhr, error){
                    errorAlert(xhr.responseText);
                    console.log(error.message);
                }
            });
        }

        function hideModal(){
            $('#infoResep').empty();
            $('#update-resep').modal('hide');
            $('#resepInputWrapper').empty();
        }


        function filterContainer(){
            let val = $('#filterInput').val();
            if(val){
                countedItem = 0;
                $('.col-xl-12.text-center').each(function(){
                    let text = $(this).text().toUpperCase();
                    if(text.includes(val.toUpperCase())){
                        $(this).show();
                        countedItem++;
                    }else{
                        $(this).hide();
                    }
                });
            }else{
                $('.col-xl-12.text-center.my-2').show();
            }
        }

        function submitResep() {
            let obatId = $(`#obatId${resepRows}`).val();
            let jumlah = $(`#jumlah${resepRows}`).val();
            if (obatId === '' || jumlah === '' || resepRows < 1 || jumlah < 1) {
                errorAlert('Tidak boleh kosong');
            } else {
                let myForm = new FormData();
                    resepRowsArray.forEach((item, index) => {
                        myForm.append('kode', $('#kodeResep').text());
                        myForm.append('obatId[]', $(`#obatId${item}`).val());
                        myForm.append('jumlah[]', $(`#jumlah${item}`).val());
                        myForm.append('catatan[]', $(`#catatan${item}`).val());
                        // myForm.append('catatanDokter', $('#catatanDokter').val());
                    });
                    const url = '/dokter/resep/create/new';
                    ajaxUpdate(url, 'POST', myForm);
                    // resepRows = 0;
                    // resepRowsArray = [];
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                }
        }

    </script>
@endsection
