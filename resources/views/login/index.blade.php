<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title>PharmaPal | {{ $title }}</title>

    <!-- Site favicon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('favicon.ico')}}" />

    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/core.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/icon-font.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/style.min.css') }}" />

</head>

<body class="login-page">
    <div class="row m-0 d-flex align-items-center">
        <div class="col-md-6 col-lg-6 col-md-12 bg-green d-flex login-image-left">
            <img src="{{ asset('src/images/amico.png') }}" alt="" class="d-flex mt-5 ml-auto mr-auto h-100" />
        </div>
        <div class="col-md-6 col-lg-6 col-md-12 py-5 align-items-center d-flex">
            <div class="container-fluid m-auto">
                <div class="login-box shadow-lg">
                    <div class="login-title">
                        <img src="{{ asset('src/images/logo-pharmapal.png') }}"
                            class="img-fluid m-auto login-title-image" alt="" />
                        <h2 class="text-center font-green">Sign In</h2>
                        <div class="border-bottom-success"></div>
                    </div>
                    <form class="bg-white px-4 pb-5 pt-5 login-body" action="/login" method="POST">
                        @csrf
                        <div class="input-group floating border-bottom {{session('email') ? 'border-danger' : ''}}">
                            <input type="email" value="{{ old('email') }}" required
                            class="form-control floating form-control-lg {{session('email') ?'form-control-danger' : ''}} border-0 rounded"
                            name="email" />
                            <label for="email">Email</label>
                        </div>
                        @if(session()->has('email'))
                        <div class="form-control-feedback text-danger ml-3" style="margin-top: -20px;">{{session('email')}}</div>
                        @endif
                        <div class="input-group floating border-bottom mt-4">
                            <input type="password" required
                                class="form-control floating border-0 form-control-lg rounded" name="password" />
                            <label for="password">Password</label>
                        </div>
                        <div class="row mt-5">
                            <div class="col-sm-12">
                                <div class="input-group mb-0">
                                    <button class="btn btn-lg btn-block btn-bg-green" type="submit">Sign In</button>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="row mt-3">
                            <div class="col-lg-12 col-md-12 col-sm-12 d-flex">
                                <div class="input-group mb-0">
                                    <div class="forgot-password text-center m-auto">
                                        <a href="#">Forgot Password ?</a>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                    </form>
                </div>
            </div>
        </div>
    </div>


</body>

</html>
