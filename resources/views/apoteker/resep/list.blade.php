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
            <div class="row py-3 px-5">
                {{-- @dd($item) --}}
                @forelse ($data as $item)
                    <div class="col-xl-12 text-center my-2">
                        <div class="bg-lightgreen rounded d-flex align-items-center row py-3 px-1">
                            <div class="col-xl-1 col-md-3">
                                {{ ($data->currentpage() - 1) * $data->perpage() + $loop->index + 1 }}
                                {{-- {{ $loop->iteration }} --}}
                            </div>
                            <div class="col-xl-2 col-md-3">
                                {{ $item->created_at->format('d/m/y') }}
                            </div>
                            <div class="col-xl-2 col-md-3">
                                {{ $item->created_at->diffForHumans() }}
                            </div>
                            <div class="col-xl-4 col-md-3">
                                {{ $item->pasien->nama }}
                            </div>
                            <div class="col-xl-3 col-md-12 py-2">
                                @if(!empty($item->alasanPenolakan))
                                <a href="#"
                                    class="btn btn-warning disabled w-100 p-2"
                                    type="button">
                                    Resep Ditolak
                                </a>
                                @elseif($item->isProceedByApoteker == '1')
                                <a href="#"
                                    class="btn bg-green w-100 p-2" style="color: #fff;"
                                    data-toggle="modal" data-target="#detail-resep-modal-{{ $item->kode }}"
                                    type="button">
                                    Lihat Resep
                                </a>
                                @else
                                <a href="#"
                                    class="btn btn-success w-100 p-2"
                                    data-toggle="modal" data-target="#detail-resep-modal-{{ $item->kode }}"
                                    type="button">
                                    Lihat Resep
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="modal fade bs-example-modal" id="detail-resep-modal-{{ $item->kode }}"
                        tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <div class="modal-title font-18" id="myLargeModalLabel">
                                        Detail Resep {{ $item->kode }}
                                    </div>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                        ×
                                    </button>
                                </div>
                                <div class="modal-body pb-3">
                                    <div class="border-bottom py-2 row">
                                        <div class="col-xl-6">
                                            <p class="font-24 mb-1">{{ $item->dokter->nama }}</p>
                                            <p>Dokter {{ $item->dokter->kategoriDokter }}</p>

                                        </div>
                                        <div class="col-xl-6 text-right">
                                            <p>Dibuat <br>{{ $item->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    <div class="border-bottom pt-3 row">
                                        <div class="col-xl-6">
                                            <p>Pasien<br>{{ $item->pasien->nama}}</p>
                                        </div>
                                        <div class="col-xl-6 text-right">
                                            @php
                                                $age = \Illuminate\Support\Carbon::parse($item->pasien->tanggal_lahir)->format('Y');
                                                $current = \Illuminate\Support\Carbon::now()->format('Y');
                                                $umur = $current - $age;
                                            @endphp
                                            <p>Usia<br>{{ $umur }} Tahun</p>
                                        </div>
                                    </div>
                                    @php
                                        $obat = \App\Models\Produk::whereIn('id', json_decode($item->obat_id))->get();
                                    @endphp
                                    @foreach ($obat as $index => $dataresep)
                                        <div class="pt-2 row border-bottom">
                                            <div class="col-xl-6">
                                                <p class="font-18 mb-1">{{ $dataresep->namaProduk }}</p>
                                                <p>{{ json_decode($item->catatan)[$index] }}</p>
                                            </div>
                                            <div class="col-xl-6 text-right">
                                                <p>
                                                    {{ json_decode($item->jumlah)[$index] }}
                                                    {{ $dataresep->satuan }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                    @if(isset($item->catatanDokter))
                                    <div class="pt-2 row border-bottom">
                                        <div class="col-12">
                                            <p class="font-18 mb-1">Catatan Dokter</p>
                                            <p>{{ $item->catatanDokter }}</p>
                                        </div>
                                    </div>
                                    @endif
                                    @if ($item->isProceedByApoteker == '1')
                                    @else
                                        <div class="border-top pt-3 row gap-3 d-flex">
                                            <div class="col-5">
                                                <button class="btn btn-outline-danger"
                                                    onclick="confirmModal('{{$item->kode}}');">
                                                    <span class="m-auto">Tolak Resep</span>
                                                </button>
                                            </div>
                                            <div class="col-5 ml-auto">
                                                <form action="/resep/confirm" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="kodeResep" value="{{$item->kode}}">
                                                    <button class="btn btn-success float-right" type="submit" onclick="return confirm('Lanjutkan untuk memproses Resep?')">
                                                        <span class="m-auto">Proses Resep</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="container-fluid bg-warning rounded" style="opacity: 0.8">
                        <div class="text-light font-20 font-weight-bold text-center py-2" style="opacity: 1;">
                            No data found
                        </div>
                    </div>
                @endforelse
            </div>

            <div id="pagin" class="row container justify-content-center d-flex pb-4">
                {{$data->links()}}
            </div>
            <a href="/apoteker/transaksi/resep" style="width: 140px;" class="fixed-bottom ml-auto btn btn-success m-4 d-flex align-items-center">
                <i class="icon-copy dw dw-add mr-2"></i> Resep Baru
            </a>
        </div>


        <div class="modal fade" id="confirm-modal" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content container">
                    <form action="/resep/reject" method="POST">
                        @csrf
                        <input type="hidden" name="kodeResep" id="kodeResep">
                        <div class="mt-3 font-weight-bold text-center font-20">
                            Konfirmasi Tolak Resep <span id="resepCode"></span>
                            <p class="text-danger font-weight-bold font-18"></p>
                        </div>
                        <div class="modal-body text-center font-18">
                            <textarea name="alasan" class="form-control rounded-0" id="alasan" cols="30" rows="10" placeholder="Masukkan alasan penolakan resep"></textarea>
                        </div>
                        <div class="my-3 row d-flex">
                            <div class="col-6">
                                <a href="#" onclick="dismissModal()"
                                    class="btn btn-outline-secondary">
                                    Batal
                                </a>
                            </div>
                            <div class="col-6 ml-auto">
                                <button type="submit"
                                    class="btn btn-danger float-right">
                                    Tolak Resep
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>


    </div>

    <script>
        let pageItems;
        let countedItem;


        function filterContainer(){
            let val = $('#filterInput').val();
            if(val){
                countedItem = 0;
                $('.col-xl-12.text-center.my-2').each(function(){
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

        function confirmModal(kode){
            $('.bs-example-modal').modal('hide');
            $('#confirm-modal').modal('show');
            $('.btn.btn-danger.float-right').addClass('disabled');
            $('#resepCode').text(kode);
            $('#kodeResep').val(kode);
        }

        $('#alasan').on('input', function(){
            let val = $(this).val();
            if(val.length < 10){
                $('.text-danger.font-weight-bold.font-18').text('Masukkan setidaknya 10 Karakter');
                $('.btn.btn-danger.float-right').addClass('disabled');
            }else{
                $('.text-danger.font-weight-bold.font-18').text('');
                $('.btn.btn-danger.float-right').removeClass('disabled');
                $('.btn.btn-danger.float-right').on('click', prosesData);
            }
        })

        function dismissModal(){
            $('.text-danger.font-weight-bold.font-18').text('');
            $('#alasan').val('');
            $('#confirm-modal').modal('hide');
        }

        // function prosesData(){
        //     let myData = new FormData();
        //     myData.append('alasan', $('#alasan').val());
        //     let kodeResep = $('#resepCode').text();
        //     ajaxUpdate('/resep/reject/'+kodeResep, 'POST', myData);
        //     location.reload();
        // }

    </script>
@endsection
