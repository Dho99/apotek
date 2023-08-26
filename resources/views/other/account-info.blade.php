@extends('layouts.main')
@section('content')
    <div class="xs-pd-20-10 pd-ltr-20 pb-5">
     {{-- @dd($data) --}}

        @foreach ($data as $item)
            <div class="card-box shadow-lg w-75 m-auto">
                <div class="container p-5 m-auto">
                    <div class="col-xl-12 d-flex">
                        @if($item->profile)
                        <img src="{{ asset('storage/'.$item->profile) }}"
                            class="m-auto img-fluid rounded-circle border border-success account-img" alt=""/>
                        @else
                        <img src="{{ asset('src/images/photo1.jpg') }}"
                            class="m-auto img-fluid rounded-circle border border-success account-img" alt=""/>
                        @endif
                    </div>
                    <div class="row my-3">
                        <div class="col-xl-6 my-3">
                            <label for="username" class="font-weight-bold">Username</label>
                            <span><i class="icon-copy bi bi-info-circle float-right font-20 mb-2" data-toggle="tooltip"
                                    title="Tooltip on top"></i></span>
                            <input type="text" class="form-control" value="{{ $item->username }}" name="username" readonly>
                        </div>
                        <div class="col-xl-6 my-3">
                            <label for="nama" class="font-weight-bold">Nama Lengkap</label>
                            <span><i class="icon-copy bi bi-info-circle float-right font-20 mb-2" data-toggle="tooltip"
                                    title="Tooltip on top"></i></span>
                            <input type="text" class="form-control" name="nama" value="{{ $item->nama }}" readonly>
                        </div>
                        <div class="col-xl-6 my-3">
                            <label for="nama" class="font-weight-bold">Email</label>
                            <span><i class="icon-copy bi bi-info-circle float-right font-20 mb-2" data-toggle="tooltip"
                                    title="Tooltip on top"></i></span>
                            <input type="text" class="form-control" name="nama" value="{{ $item->email }}" readonly>
                        </div>
                        <div class="col-xl-6 my-3">
                            <label for="nama" class="font-weight-bold">Email</label>
                            <span><i class="icon-copy bi bi-info-circle float-right font-20 mb-2" data-toggle="tooltip"
                                    title="Tooltip on top"></i></span>
                            <input type="text" class="form-control" name="nama" value="{{ $item->level }}" readonly>
                        </div>
                        <div class="col-xl-12 mt-4 d-flex">
                            <a href="/apoteker/account/edit/{{$item->kode}}" class="btn btn-success m-auto w-50">Edit Akun</a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

    </div>
@endsection
