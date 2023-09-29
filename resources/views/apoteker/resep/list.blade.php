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
                @foreach ($data as $item)
                    <div class="col-xl-12 text-center my-2">
                        <div class="bg-lightgreen rounded d-flex align-items-center row py-3 px-1">
                            <div class="col-xl-1 col-md-3">
                                {{-- {{ $data->currentPage() *  $loop->iteration }} --}}
                                {{ $loop->iteration }}
                            </div>
                            <div class="col-xl-2 col-md-3">
                                {{ $item['created_at']->format('d/m/y') }}
                            </div>
                            <div class="col-xl-2 col-md-3">
                                {{ $item['created_at']->format('h:i') }}
                            </div>
                            <div class="col-xl-4 col-md-3">
                                {{ $item['pasien'] }}
                            </div>
                            <div class="col-xl-3 col-md-12 py-2">
                                <a href="#"
                                    class="btn {{ $item['isProceedByApoteker'] == '1' ? 'btn-success' : 'btn-danger' }} w-100 p-2"
                                    data-toggle="modal" data-target="#detail-resep-modal-{{ $item['kodeTransaksi'] }}"
                                    type="button">
                                    Lihat Resep
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade bs-example-modal" id="detail-resep-modal-{{ $item['kodeTransaksi'] }}"
                        tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <div class="modal-title  font-18" id="myLargeModalLabel">
                                        Detail Resep {{ $item['kodeTransaksi'] }}
                                    </div>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                        ×
                                    </button>
                                </div>
                                <div class="modal-body pb-3">
                                    <div class="border-bottom py-2 px-5 row">
                                        <div class="col-xl-6">
                                            @foreach ($item['dokter'] as $key => $dokter)
                                                <p class="font-24 mb-1">{{ $dokter }}</p>
                                                <p>Dokter {{ $key }}</p>
                                            @endforeach
                                        </div>
                                        <div class="col-xl-6 text-right">
                                            <p>Dibuat Pada {{ $item['created_at'] }}</p>
                                        </div>
                                    </div>
                                    <div class="border-bottom pt-3 px-5 row">
                                        <div class="col-xl-6">
                                            <p>{{ $item['pasien'] }}</p>
                                        </div>
                                        <div class="col-xl-6 text-right">
                                            <p>{{ $item['umur'] }} Tahun</p>
                                        </div>
                                    </div>
                                    @foreach ($item['obat'] as $key => $obat)
                                        <div class="pt-2 px-5 row">
                                            <div class="col-xl-6">
                                                <p class="font-18 mb-1">{{ $obat }}</p>
                                                <p>{{ $item['catatan'][$key] }}</p>
                                            </div>
                                            <div class="col-xl-6 text-right">
                                                <p>
                                                    {{ $item['jumlah'][$key] }}
                                                    {{ $item['satuan'][$key] }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                    @if ($item['isProceedByApoteker'] == '1')
                                    @else
                                        <div class="border-top pt-3">
                                            <button class="w-50 btn btn-info m-auto d-flex"
                                                onclick="if (confirm('Beralih ke Proses Resep?')) window.location.href = '/apoteker/resep/antrian';">
                                                <span class="m-auto">Proses Resep</span>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div id="pagin" class="row container justify-content-center d-flex pb-4">
                <ul class="pagination" style="overflow-x: scroll;">
                    <li class="page-item active m-auto"><a class="page-link" href="#">1</a></li>
                </ul>
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


            originalItemCount = $('.col-xl-12.text-center.my-2').length;
            countedItem = originalItemCount;
            if (countedItem % 10 === 0) {
                pageItems = countedItem / 10;
            } else {
                pageItems = Math.floor(countedItem / 10) + 1
            }

            for (let i = 2; i <= pageItems; i++) {
                $('.pagination').append(`
                <li class="page-item"><a class="page-link" href="#">${i}</a></li>
            `);
            }
            pageSize = 10;

            showPage = function(page) {
                $(".col-xl-12.text-center.my-2").hide();
                $(".col-xl-12.text-center.my-2").each(function(n) {
                    if (n >= pageSize * (page - 1) && n < pageSize * page)
                        $(this).show();
                });
            }

            showPage(1);

            $("#pagin li").click(function() {
                $("#pagin li").removeClass('active');
                $(this).addClass("active");
                showPage(parseInt($(this).text()))
            });


    </script>
@endsection
