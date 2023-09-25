@extends('layouts.main')
@section('content')
    <div class="xs-pd-20-10 pd-ltr-20">
        <div class="title pb-20">
            <h2 class="h3 mb-0">{{ $title }}</h2>
        </div>

        <div class="card-box mb-30">

        <div class="row p-5">
            @foreach ($data as $item)
                <div class="col-xl-12 text-center">

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
                            <a href="#" class="btn btn-success w-100 p-2" data-toggle="modal"
                                data-target="#detail-resep-modal-{{ $item->kode }}" type="button">
                                Lihat Resep
                            </a>
                        </div>
                    </div>
                </div>
                <div class="modal fade bs-example-modal" id="detail-resep-modal-{{ $item->kode }}" tabindex="-1"
                    role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal modal-dialog-centered">
                        <div class="modal-content">
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
                                        <p>Dibuat Pada {{ $item->created_at->format('H:i') }}</p>
                                    </div>
                                </div>
                                <div class="border-bottom pt-3 px-5 row">
                                    <div class="col-xl-6">
                                        <p>{{ $item->pasien->nama }}</p>
                                    </div>
                                    <div class="col-xl-6 text-right">
                                        <p>{{ $item->pasien->umur }} Tahun</p>
                                    </div>
                                </div>
                                <div class="pt-3 px-5 row border-bottom">
                                    <div class="col-xl-6">
                                        <p class="font-18 mb-1">{{ $item->obat->namaProduk }}</p>
                                        <p>{{ $item->catatan }}</p>
                                    </div>
                                    <div class="col-xl-6 text-right">
                                        <p>{{ $item->jumlah }} {{ $item->obat->satuan }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{ $data->links() }}

        </div>

    </div>
@endsection
