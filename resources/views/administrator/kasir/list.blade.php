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
@if(auth()->user()->role->roleName === 'Administrator')
    <div class="py-3 newTrxbtnSection position-lg-relative position-md-sticky position-sticky sticky-top d-flex">
        <a href="{{route('kasir.create')}}" class="btn btn-sm btn-success ml-auto">Tambah Produk</a>
    </div>
@endif
<div class="container bg-white rounded py-3 px-2 mt-3">
    <table id="pasienTable" class="table text-center" style="width: 100%;">
        <thead>
            <tr>
                <th data-dt-order="disable">No</th>
                <th>Nama Kasir</th>
                <th>Bergabung Sejak</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cashiers as $d)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $d->nama }}</td>
                    <td>{{ $d->created_at->format('d F Y') }}</td>
                    <td class="">
                        <div class="d-flex">
                            <a href="{{route('dokter.show',[$d->id])}}" class="btn btn-sm btn-secondary"><i
                                    class="icon-copy dw dw-edit1 d-flex align-items-center"></i></a>
                            <form class="d-inline-flex" method="POST" action="{{route('dokter.destroy',[$d->id])}}" onclick="return confirm('Apakah anda yakin menghapus data ini ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i
                                    class="icon-copy dw dw-trash d-flex align-items-center"></i></button>
                            </form>
                        </div>
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
        $('#pasienTable').DataTable({
            responsive: true
        });
    });
</script>
@endpush
