@extends('layouts.main')
@section('content')
    <div class="xs-pd-20-10 pd-ltr-20">
        <div class="title pb-20">
            <h2 class="h3 mb-0">{{ $title }}</h2>
            <p class="font-weight-bold font-20 mt-2">Antrian Resep</p>
        </div>

        <div class="container-fluid py-3 px-0 ">
            <div class="swiper mySwiper">
                <div class="swiper-wrapper">
                    {{-- @foreach ($produk as $item)
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
                                                {{ $item->id }}
                                            </p>
                                            <p class="">
                                                {{ $item }} Tahun
                                            </p>
                                        </div>

                                    </div>

                                </div>

                                </a>
                        </div>
                    @endforeach --}}

                </div>
            </div>
        </div>

        <h5 class="h4 mb-20">Katalog Obat</h5>
        <div class="tab my-2">
            <ul class="nav nav-tabs customtab border-0" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#semua" role="tab"
                        aria-selected="true">Semua</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#tablet" role="tab" aria-selected="false">Tablet</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#kapsul" role="tab" aria-selected="false">Kapsul</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#pill" role="tab" aria-selected="false">Pill</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#obatCair" role="tab" aria-selected="false">Obat
                        Cair</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#oles" role="tab" aria-selected="false">Oles</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#injeksi" role="tab" aria-selected="false">Injeksi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#injeksi" role="tab" aria-selected="false">Lain - Lain</a>
                </li>
                <li class="nav-item ml-auto mb-2">
                    <input type="text" name="" id="" placeholder="Search Item" class="form-control">
                </li>

            </ul>
            <div class="tab-content" id="tabContent">


            </div>
        </div>


    </div>
@endsection

<script>

  jQuery(document).ready(function() {
    filterKatalog('Semua');
  });

function filterKatalog(satuan){
    const hasil = `${satuan}`;
    console.log(hasil);
}


</script>
