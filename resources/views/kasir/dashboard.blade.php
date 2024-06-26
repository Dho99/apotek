@extends('layouts.main')
@section('styles')
<style>
    .newTrxbtnSection{
        margin-top: -50px;
    }

    @media screen and (width < 768px){
        .newTrxbtnSection{
            top: 60px !important;
        }
    }
</style>
@endsection
@section('content')
<div class="py-3 newTrxbtnSection position-lg-relative position-md-sticky position-sticky sticky-top">
    <button class="btn btn-sm btn-success d-flex ml-auto">Transaksi Baru</button>
</div>
    <div class="row my-3">
        <div class="col-lg-6 col-md-6 col-12 mb-20">
            <div class="bg-white shadow rounded-lg">
                <div class="row pt-2 py-3 px-4">
                    <div class="col-12 d-flex">
                        <h6 class="d-flex align-items-center">Jumlah Transaksi</h6>
                        <div class="badge bg-lightgreen ml-auto">{{ $thisYear->format('Y') }}</div>
                    </div>
                    <div class="col-12 my-2">
                        <h4>{{ $countTransaction }} <span class="ml-3"></span></h4>
                    </div>
                    <div class="col-12 my-2">
                        <a href="/" class="text-success text-decoration-none weight-700">Lihat Detail</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-12 mb-20">
            <div class="bg-white shadow rounded-lg">
                <div class="row pt-2 py-3 px-4">
                    <div class="col-12 d-flex">
                        <h6 class="d-flex align-items-center">Total Transaksi</h6>
                        <div class="badge bg-lightgreen ml-auto">{{ $thisYear->format('Y') }}</div>
                    </div>
                    <div class="col-12 my-2">
                        <h4>@currency($calculateTransaction) <span class="ml-3"></span></h4>
                    </div>
                    <div class="col-12 my-2">
                        <a href="/" class="text-success text-decoration-none weight-700">Lihat Detail</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-12 mb-20">
            <div class="bg-white shadow rounded-lg">
                <div class="row pt-2 py-3 px-4">
                    <div class="col-12 d-flex">
                        <h6 class="d-flex align-items-center">Total Produk</h6>
                    </div>
                    <div class="col-12 my-2">
                        <h4>{{ $totalProduct }}<span class="ml-3"></span></h4>
                    </div>
                    <div class="col-12 my-2">
                        <a href="/" class="text-success text-decoration-none weight-700">Lihat Detail</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-12 mb-20">
            <div class="bg-white shadow rounded-lg">
                <div class="row pt-2 py-3 px-4">
                    <div class="col-12 d-flex">
                        <h6 class="d-flex align-items-center">Produk Kadaluarsa</h6>
                    </div>
                    <div class="col-12 my-2">
                        <h4>{{ $expiredProduct }}<span class="ml-3"></span></h4>
                    </div>
                    <div class="col-12 my-2">
                        <a href="/" class="text-success text-decoration-none weight-700">Lihat Detail</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 mb-20">
            <div class="bg-white shadow rounded-lg">
                <div class="row pt-2 py-3 px-4">
                    <div class="col-12 d-flex">
                        <h6 class="d-flex align-items-center">Grafik Penjualan</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
