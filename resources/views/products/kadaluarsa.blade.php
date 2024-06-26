@extends('layouts.main')
@section('content')

<div class="container bg-white rounded py-3 px-2 mt-3">
    <table id="productTable" class="table" style="width: 100%;">
        <thead>
            <tr>
                <th>No</th>
                <th>Kategori</th>
                <th>Nama Produk</th>
                <th>Stok</th>
                <th>Harga Beli</th>
                <th>Harga Jual</th>
                <th>Tgl. Pembaruan Stok</th>
                <th>Tgl. Kadaluarsa</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$product->namaProduk}}</td>
                    <td>{{$product->golongan->golongan}}</td>
                    <td>{{$product->stok}}</td>
                    <td>@currency($product->hargaBeli)</td>
                    <td>@currency($product->hargaJual)</td>
                    <td>{{$product->updated_at->format('d F Y')}}</td>
                    <td>{{$product->expDate->format('d F Y')}}</td>
                    <td>
                        <a href="{{route('products.show',[$product->id])}}" class="btn btn-sm btn-info">
                            <i class="icon-copy dw dw-information"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>


@endsection
@push('scripts')

<script>
    $(function(){
        $('#productTable').DataTable({
            responsive: true,
        });
    });
</script>

@endpush
