@extends('layouts.main')
@section('content')
    <div class="xs-pd-20-10 pd-ltr-20">
        <div class="title pb-20">
            <h2 class="h3 mb-0">{{ $title }}</h2>
        </div>

        <div class="row pb-10">
            <div class="col-xl-6 col-lg-6 col-md-12 mb-20">
                <div class="card-box height-100-p widget-style3">
                    <div class="d-flex flex-wrap">
                        <div class="widget-data">
                            <div class="font-20 text-secondary weight-500">
                                Diagram Penjualan
                            </div>
                        </div>
                        <div id="chart3" class="container"></div>
                        <a href="#" class="text-dark mb-3 px-4">Lihat Selengkapnya</a>
                    </div>
                </div>

            </div>

            <div class="col-xl-6 col-lg-6 col-md-12 mb-20">
                <div class="card-box height-100-p widget-style3">
                    <div class="d-flex flex-wrap">
                        <div class="widget-data w-100">
                            <div class="font-20 text-secondary weight-500">
                                Data Stock Obat
                            </div>

                            @if (count($obat) === 0)
                                <div class="container-fluid my-3 rounded-lg">
                                    <div class="row bg-lightgreen">
                                        <div class="col-12">
                                            No Data
                                        </div>
                                    </div>
                                </div>
                            @else
                                {{-- @dd    ($obat) --}}
                                @foreach ($obat as $item)
                                    <div class="container-fluid my-3 rounded-lg">
                                        <div class="row bg-lightgreen text-center align-items-center py-2">
                                            <div class="col-lg-1">
                                                <div class="rounded-lg">
                                                    {{ $loop->iteration }}
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="rounded-lg">
                                                    {{ $item->updated_at->format('d-m-Y') }}
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="rounded-lg">
                                                    {{ $item->namaProduk }}
                                                </div>
                                            </div>

                                            <div class="col-lg-4 w-50 m-auto">
                                                @if ($item->stok <= 20 && $item->stok >= 10)
                                                    <div class="small bg-orange text-light rounded-lg p-1">
                                                        Stok Menipis
                                                    </div>
                                                @elseif ($item->stok <= 0 && $item->stok <= 10)
                                                    <div class="small bg-danger text-light rounded-lg p-1">
                                                        Stok Habis
                                                    </div>
                                                @else
                                                    <div class="small bg-success text-light rounded-lg p-1">
                                                        Stok Tersedia
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                            <a href="#" class="px-3 mb-4 text-dark">Lihat Selengkapnya</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="row pb-10">
            <div class="col-xl-7 col-lg-8 col-md-12 col-sm-12 mb-20">
                <div class="card-box height-100-p widget-style3">
                    <div class="d-flex flex-wrap">
                        <div class="widget-data w-100">
                            <div class="font-20 text-secondary pb-3 weight-500">
                                Daftar Resep Masuk
                            </div>

                            {{-- @dd($data) --}}
                            @foreach ($data as $item)
                                <div class="bg-lightgreen my-3 rounded-lg">
                                    <div class="row py-1 w-100 m-auto text-center align-items-center">
                                        <div class="col-xl-1 col-md-4 col-sm-6">
                                            <div class="rounded-lg py-2">
                                                {{ $loop->iteration }}
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-4 col-sm-6">
                                            <div class="rounded-lg px-0 py-0">
                                                {{ $item->created_at->format('d-m-Y') }}
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-md-4 col-sm-6">
                                            <div class="rounded-lg py-2">
                                                {{ $item->pasien->nama }}
                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-md-4 col-sm-6 text-center small align-items-center d-flex">
                                            @if ($item->isProceedByApoteker = 1)
                                                <div class="rounded-lg text-light bg-success p-2 m-auto">
                                                    Sudah Dibuat
                                                </div>
                                            @else
                                                <div class="rounded-lg text-light bg-danger p-2 m-auto">
                                                    Sudah Dibuat
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-xl-1 col-md-4 col-sm-6 ">
                                            <button class="border-0 mt-1 bg-transparent text-center text-danger font-24"
                                                data-toggle="modal"
                                                data-target="#daftar-resep-dashboard-modal-{{ $item->kode }}" type="button">
                                                <i class="icon-copy dw dw-edit"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade bs-example-modal-lg"
                                    id="daftar-resep-dashboard-modal-{{ $item->kode }}" tabindex="-1" role="dialog"
                                    aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                <div class="row border-bottom">
                                                    <div class="col-lg-6">
                                                        <p class="mb-1 font-18">Dr. {{ $item->dokter->nama }}</p>
                                                        <p>{{ $item->dokter->kategoriDokter }}</p>
                                                    </div>
                                                    <div class="col-lg-6 text-right">
                                                        <p>{{ $item->created_at->format('d:m:Y H:i') }}</p>
                                                    </div>
                                                </div>
                                                {{-- <div class="dropdown-divider"></div> --}}
                                                <div class="row my-2 border-bottom">
                                                    <div class="col-lg-6">
                                                        <p class="font-18">{{ $item->pasien->nama }}</p>
                                                    </div>
                                                    <div class="col-lg-6 text-right">
                                                        <p>{{ $item->umur }}</p>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <p>{{ $item->gejala }}</p>
                                                    </div>
                                                </div>
                                                <div class="row d-flex font-weight-bold">
                                                    <div class="col-6">
                                                        Nama Produk
                                                    </div>
                                                    <div class="col-3">
                                                        Jumlah
                                                    </div>
                                                    <div class="col-3">
                                                        Satuan
                                                    </div>
                                                </div>
                                                @foreach ($resepMasuk as $index => $item)
                                                    @foreach ($item['namaProduk'] as $namaObatIndex => $namaObat)
                                                        <div class="row py-2 border-bottom">
                                                            <div class="col-6">
                                                                {{ $namaObat }} <br>
                                                                {{ $item['catatan'][$namaObatIndex] }}
                                                            </div>
                                                            <div class="col-3">
                                                                {{ $item['jumlah'][$namaObatIndex] }} <br>

                                                            </div>
                                                            <div class="col-3">
                                                                {{ $item['satuan'][$namaObatIndex] }}
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endforeach
                                            </div>

                                            <div class="modal-footer border-0 mb-3">
                                                <form action="/apoteker/resep/proses/{{ $item['kode'] }}" method="POST"
                                                    class="w-100 d-flex">
                                                    @csrf
                                                    @if ($item['isProceedByAdmin'] = 1)
                                                        <button type="button" class="btn btn-secondary disabled w-75 m-auto"
                                                            data-dismiss="modal">
                                                            Resep Sudah Diproses
                                                        </button>
                                                    @else
                                                        <button type="submit"
                                                            class="btn btn{{ $item['proceedByApoteker'] = 1 ? '-outline' : '-btn' }}-success w-75 m-auto">
                                                            Proses Resep Sekarang
                                                        </button>
                                                    @endif
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach





                            <a href="#" class="px-3 mb-4 text-dark sticky-bottom">Lihat Selengkapnya</a>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-xl-5 col-lg-4 col-md-12 col-sm-12 mb-20">
                <div class="card-box height-100-p widget-style3">
                    <div class="d-flex flex-wrap">
                        <div class="widget-data w-100">
                            <div class="font-20 pb-4 text-secondary weight-500">
                                Data Dokter
                            </div>

                            @if (count($isPresent) === 0)
                                <div class="container-fluid rounded-lg py-2">
                                    <div class="row text-center font-18">
                                        <div class="col-12">
                                            Belum Ada Dokter yang Hadir :(
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @foreach ($isPresent as $item)
                                <div class="container-fluid rounded-lg">
                                    <div class="row bg-lightgreen py-2 text-center align-items-center">
                                        <div class="col-xl-1 col-md-6">
                                            <div class=" rounded-lg text-center">
                                                {{ $loop->iteration }}
                                            </div>
                                        </div>
                                        <div class="col-xl-5 col-md-6">
                                            <div class=" rounded-lg text-center">
                                                @php
                                                    $nama = explode(' ', $item->nama);
                                                    $namaDepan = array_shift($nama);
                                                @endphp
                                                Dr. {{ $namaDepan }}

                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-6">
                                            <div class=" rounded-lg text-center">
                                                {{ $item->kategoriDokter }}
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-6 w-50 m-auto">
                                            <div class="small bg-info text-light rounded-lg text-center p-1">
                                                Present
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach


                        </div>
                        {{-- <a href="#" class="px-3 mb-4 text-dark">Lihat Selengkapnya</a> --}}
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection
