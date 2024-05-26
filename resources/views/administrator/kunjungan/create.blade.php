@extends('layouts.main')
@section('content')
    <div class="container bg-white rounded py-3 px-2 mt-3">
        <form action="{{ route('kunjungan.store') }}" class="d-flex px-4 row" method="POST">
            @csrf
            <div class="form-group col-12">
                <label for="">No Rekam Medis</label>
                <select name="" id="rmSelect" class="form-control" style="width: 100%;">
                    <option value="">Cari No Rekam Medis</option>
                    @foreach ($no_rm as $n)
                        <option value="{{ $n->id }}">{{ $n->no_rekam_medis }} - {{ $n->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-4">
                <label for="">Kode Pasien</label>
                <input type="text" class="form-control" id="kode" name="kode">
            </div>
            <div class="form-group col-4">
                <label for="">Nama Lengkap</label>
                <input type="text" class="form-control" id="nama" name="nama">
            </div>
            <div class="form-group col-4">
                <label for="">Status</label>
                <input type="text" class="form-control" id="status" name="status">
            </div>
            <div class="form-group col-12">
                <label for="">Keluhan</label>
                <textarea class="form-control" name="keluhan" id="keluhan" cols="30" rows="10"></textarea>
            </div>
            <div class="col-3">
                <a href="/administrator/dashboard" class="w-100 btn btn-secondary">Batal</a>
            </div>
            <div class="col-3 ml-auto">
                <button type="submit" class="w-100 btn btn-success">Simpan</button>
            </div>
        </form>
    </div>
@endsection
@push('scripts')
    <script>
        $(function() {
            $('#kode, #nama, #status, #keluhan, #tindakan').attr('disabled', 'disabled');
            $('#rmSelect').select2();
            $('button[type="submit"]').attr('disabled','disabled');
        });

        $('#rmSelect').on('change', (() => {
            let val = $('#rmSelect').val();
            const route = "{{route('pasien.index')}}";
            let url = `${route}/${val}`
            asyncAjaxUpdate(url, 'GET', null).then((response) => {
                const result = response.result;
                if (typeof result !== 'undefined') {
                    $('#kode, #nama, #status, #keluhan, #tindakan').removeAttr('disabled');
                    $('#kode').val(result.kode);
                    $('#nama').val(result.nama);
                    $('#status').val(result.status);
                    $('button[type="submit"]').removeAttr('disabled');
                } else {
                    errorAlert('Data tidak ditemukan');
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                }
            }).catch((error) => {
                console.log(error);
            });
        }));
    </script>
@endpush
