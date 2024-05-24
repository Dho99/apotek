@extends('layouts.main')
@section('styles')
    <style>
        .card-box {
            width: 85% !important;
        }
    </style>
@endsection
@section('content')
    <div class="mt-4 pb-5">

        {{-- @foreach ($data as $item) --}}
        @php
            $role = \Illuminate\Support\Str::lower($data->role->roleName);
        @endphp

        <form action="{{ route($role.'.update', [$data->id]) }}" method="POST" enctype="multipart/form-data">
            @method('PUT')

            @csrf
            <div class="card-box shadow-lg m-auto mb-5 py-4">
                {{-- @if ($errors)
                    <div class="container">
                        @foreach ($errors->all() as $err)
                            <div class="alert alert-danger mt-3" role="alert">
                                {{ $err }}
                            </div>
                        @endforeach
                    </div>
                @endif --}}
                <div class="modal fade bs-example-modal-lg" id="edit-image-account-modal" tabindex="-1" role="dialog"
                    aria-labelledby="myLargeModalLabel" aria-hidden="true">

                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myLargeModalLabel">
                                    Edit Foto Profil
                                </h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                    ×
                                </button>
                            </div>
                            <div class="modal-body d-flex">
                                <div class="container row m-auto">
                                    <div class="col-xl-12 mb-3">
                                        <input type="file" src="" alt="" id="image"
                                            class="form-control" name="profile" onchange="previewImage()" value="">
                                    </div>
                                    <img src=""
                                        class="img-preview rounded-circle img-fluid account-img border border-success d-none">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal"
                                    onclick="undoChanges()">
                                    Batal
                                </button>
                                <button type="button" class="btn btn-primary" data-dismiss="modal">
                                    Simpan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="container  m-auto row">
                    <div class="col-xl-6 d-flex">
                        <div class="da-card m-auto rounded-circle border border-success">
                            <div class="da-card-photo">
                                <div class="account-img">
                                    <img src="{{ asset('storage/' . $data->profile) }}" class="w-100 h-100"
                                        id="myProfile" />
                                </div>
                                <div class="da-overlay">
                                    <div class="da-social">
                                        <ul class="clearfix">
                                            <li>
                                                <a href="#" data-toggle="modal"
                                                    data-target="#edit-image-account-modal" type="button"
                                                    class="text-decoration-none d-flex">
                                                    <i class="icon-copy dw dw-pencil-1 border-light m-auto"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="my-3 col-xl-6">
                        @if ($data->role->roleName == 'Pasien')
                            <div class="col-xl-12 my-3">
                                <label for="username" class="font-weight-bold">No Rekam Medis</label>
                                <input type="text" class="form-control" value="{{ $data->no_rekam_medis }}"
                                    name="no_rekam_medis" required disabled>
                            </div>
                        @endif
                        <div class="col-xl-12 my-3">
                            <label for="username" class="font-weight-bold">Username</label>
                            <input type="text" class="form-control @error('username') is-invalid @enderror" value="{{ $data->username }}" name="username"
                                required>
                            @error('username')
                                <div class="form-control-feedback text-danger">
                                    {{$message}}
                                </div>
                            @enderror
                        </div>
                        <div class="col-xl-12 my-3">
                            <label for="nama" class="font-weight-bold">Nama Lengkap</label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" name="nama" value="{{ $data->nama }}" required>
                            @error('nama')
                                <div class="form-control-feedback text-danger">
                                    {{$message}}
                                </div>
                            @enderror
                        </div>
                        <div class="col-xl-12 my-3">
                            <label for="nama" class="font-weight-bold">Alamat</label>
                            <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" id="">{{ $data->alamat }}</textarea>
                            @error('alamat')
                                <div class="form-control-feedback text-danger">
                                    {{$message}}
                                </div>
                            @enderror
                        </div>
                        <div class="col-xl-12 my-3">
                            <label for="nama" class="font-weight-bold">Email</label>
                            <input type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $data->email }}" required>
                            @error('email')
                                <div class="form-control-feedback text-danger">
                                    {{$message}}
                                </div>
                            @enderror
                        </div>
                        <div class="col-xl-12 my-3">
                            <label for="nama" class="font-weight-bold">Tanggal Lahir</label>
                            <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror" name="tanggal_lahir"
                                value="{{ $data->tanggal_lahir }}" required>
                                @error('tanggal_lahir')
                                    <div class="form-control-feedback text-danger">
                                        {{$message}}
                                    </div>
                                @enderror
                        </div>
                        @if ($data->role->roleName === 'Dokter')
                            @php
                                $practiceTime = json_decode($data->jamPraktek);
                                $start = $practiceTime->start;
                                $end = $practiceTime->end;
                            @endphp
                            <div class="col-12 row mx-0 px-0 py-3">
                                <h5 class="text-center col-12 mb-1">Jam Praktek</h5>
                                <div class="col-lg-6 col-12">
                                    <label for="nama" class="font-weight-bold">Mulai</label>
                                    <input type="time" class="form-control @error('start') is-invalid @enderror" name="start"
                                        value="{{ $start }}">
                                        @error('start')
                                            <div class="form-control-feedback text-danger">
                                                {{$message}}
                                            </div>
                                        @enderror
                                </div>
                                <div class="col-lg-6 col-12">
                                    <label for="nama" class="font-weight-bold">Akhir</label>
                                    <input type="time" class="form-control @error('end') is-invalid @enderror" name="end"
                                        value="{{ $end }}">
                                        @error('end')
                                            <div class="form-control-feedback text-danger">
                                                {{$message}}
                                            </div>
                                        @enderror
                                </div>
                            </div>
                        @endif

                        <div class="col-xl-12 my-3">
                            <label for="nama" class="font-weight-bold">Password</label>
                            <input type="password" class="form-control" name="password" id="pass1" required
                                oninput="validate1()" onblur="cancelValidate()">
                            <p id="vpass1" class="text-danger font-14"></p>
                        </div>
                        <div class="col-xl-12 mt-4 d-flex">
                            <button type="submit" class="btn btn-success w-75 m-auto" id="submitBtn"
                                disabled>Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        {{-- @endforeach --}}
    </div>
@endsection
@push('scripts')
    <script>
        function cancelValidate() {
            const alertInput1 = document.getElementById('vpass1');
            const inputVal = document.getElementById('pass1');
            const submitBtn = document.getElementById('submitBtn');
            if (value.length < 8) {
                value = inputVal.value;
                alertInput1.style.display = 'block';
                alertInput1.innerText = `Panjang Password anda Setidaknya 8 Huruf`;
                submitBtn.disabled = 'disabled';
            } else {
                alertInput1.style.display = 'none';
                submitBtn.disabled = '';
            }
        }

        function validate1() {
            const alertInput1 = document.getElementById('vpass1');
            const inputVal = document.getElementById('pass1');
            const submitBtn = document.getElementById('submitBtn');
            value = inputVal.value;
            if (value.length < 8) {
                alertInput1.style.display = 'block';
                alertInput1.innerText = `Panjang Password anda Setidaknya 8 Huruf`;
                submitBtn.disabled = 'disabled';
            } else {
                alertInput1.style.display = 'none';
                submitBtn.disabled = '';
            }
        }

        // $('#submitBtn').on('click', function(event) {
        //     if ($('#roleSelect').val() === '') {
        //         errorAlert('Periksa Kembali data Anda !');
        //         $('#roleSelect').addClass('is-invalid');
        //         event.preventDefault();
        //     } else {
        //         $('form').submit();
        //     }
        // });

        function undoChanges() {
            const myProfileImage = document.getElementById('myProfile');
            myProfileImage.src =
                '{{ $data->profile ? asset('storage/' . $data->profile) : asset('src/images/photo1.jpg') }}';
            document.getElementById('image').value = "";
            document.querySelector('.img-preview').style.display = 'none';
        }

        function previewImage() {
            document.querySelector('.img-preview').classList.remove('d-none');
            const image = document.querySelector('#image');
            const imgPreview = document.querySelector('.img-preview');
            const myProfileImage = document.getElementById('myProfile');

            imgPreview.style.display = 'flex';

            const oFReader = new FileReader();
            oFReader.readAsDataURL(image.files[0]);

            oFReader.onload = function(oFREvent) {
                imgPreview.src = oFREvent.target.result;
                myProfileImage.src = oFREvent.target.result;
                myProfileImage.style.height = '155px';
                myProfileImage.style.width = '155px';
            }
        }
    </script>
@endpush
