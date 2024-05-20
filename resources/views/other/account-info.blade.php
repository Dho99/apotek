@extends('layouts.main')
@section('content')
    <div class="xs-pd-20-10 pd-ltr-20 pb-5">
     {{-- @dd($data) --}}

        {{-- @foreach ($data as $item) --}}
            <div class="card-box shadow-lg w-75 m-auto">
                <div class="container p-5 m-auto">
                    <div class="col-xl-12 d-flex">
                        @if($data->profile)
                        <img src="{{ asset('storage/'.$data->profile) }}"
                            class="m-auto img-fluid rounded-circle border border-success account-img" alt=""/>
                        @else
                        <img src="{{ asset('src/images/photo1.jpg') }}"
                            class="m-auto img-fluid rounded-circle border border-success account-img" alt=""/>
                        @endif
                    </div>
                    <div class="row my-3">
                        <div class="col-xl-6 my-3">
                            <label for="username" class="font-weight-bold">Username</label>
                            <input type="text" class="form-control" value="{{ $data->username }}" name="username" readonly>
                        </div>
                        <div class="col-xl-6 my-3">
                            <label for="nama" class="font-weight-bold">Nama Lengkap</label>
                            <input type="text" class="form-control" name="nama" value="{{ $data->nama }}" readonly>
                        </div>
                        <div class="col-xl-6 my-3">
                            <label for="nama" class="font-weight-bold">Email</label>
                            <input type="text" class="form-control" name="nama" value="{{ $data->email }}" readonly>
                        </div>
                        <div class="col-xl-6 my-3">
                            <label for="nama" class="font-weight-bold">Peran</label>
                            <input type="text" class="form-control" name="nama" value="{{ $data->role->roleName }}" readonly>
                        </div>
                        @if($data->role->roleName == 'Dokter')
                            <div class="col-xl-12 mt-4 d-flex">
                                <a href="{{route('dokter.edit', [$data->id])}}" class="btn btn-success m-auto w-50">Edit Akun</a>
                            </div>
                        @else
                            <div class="col-xl-12 mt-4 d-flex">
                                <a href="/account/edit/{{$data->kode}}" class="btn btn-success m-auto w-50">Edit Akun</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        {{-- @endforeach --}}

    </div>
@endsection
