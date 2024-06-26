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
        <a href="#" class="btn btn-sm btn-success ml-auto" onclick="addNewCategory()">Tambah Supplier</a>
    </div>
@endif
    <div class="xs-pd-20-10 pd-ltr-20">
        <div class="card-box mb-30 pt-3">
            <div class="pb-20 container">
                <table class="data-table table nowrap" id="myKategoriTable">
                    <thead>
                        <tr>
                            <th class="table-plus datatable-nosort">No</th>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th class="datatable-nosort">Nomor Telephone</th>
                            <th class="datatable-nosort">Petugas</th>
                            <th class="">Created At</th>
                            <th class="datatable-nosort">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $s)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$s->kode}}</td>
                                <td>{{$s->nama}}</td>
                                <td>{{$s->alamat}}</td>
                                <td>{{$s->noTel}}</td>
                                <td>{{$s->perwakilan}}</td>
                                <td>{{$s->created_at->format('d F Y')}}</td>
                                <td>
                                    <button class="btn btn-sm btn-success" onclick="editCategory('{{$k->id}}')" id="golongan{{$k->id}}" data-link="{{route('supplier.show',[$k->golongan])}}">Detail</button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteCategory('{{$k->id}}')" id="golongan{{$k->id}}" data-link="{{route('supplier.show',[$k->golongan])}}">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addKategoriModal" role="dialog" data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body font-18">
                    <div class="modal-title text-center font-weight-bold font-24">
                        <div id="title"></div>
                    </div>
                    <form action="#" id="dataKategoriForm" onsubmit="updateKategori(event)">
                        <div class="form-group mt-4">
                            <label class="font-weight-bold d-flex">Kode</label>
                            <input class="form-control" name="" value="" type="text" id="kode" readonly
                                required />
                        </div>
                        <div class="form-group mb-4">
                            <label class="font-weight-bold">Nama</label>
                            <input class="form-control" name="" value="" type="text" id="nama"
                                readonly required />
                        </div>
                        <div class="form-group mb-4">
                            <label class="font-weight-bold">Alamat</label>
                            <textarea name="" class="form-control" id="alamat" cols="30" rows="10" required></textarea>
                        </div>
                        <div class="form-group mb-4">
                            <label class="font-weight-bold">No Telephone</label>
                            <input class="form-control" name="" value="" type="text" id="noTelp"
                                readonly required />
                        </div>
                        <div class="form-group d-flex">
                            <button type="button" onclick="emptyModal()" class="btn btn-secondary">Tutup</button>
                            <button type="button" onclick="changeToEdit('#dataKategoriForm')" id="editBtn"
                                class="btn btn-info ml-auto">Edit</button>
                            <button type="submit" id="updateBtn" class="btn btn-success d-none ml-auto">Simpan</button>
                        </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script src="{{asset('src/plugins/inputmask-jquery/jquery.mask.min.js')}}"></script>
<script>
    $().ready(function() {
        // refreshTable();
        $('input#noTelp').mask('(+62) 0000-0000-0000');
    });


    function addNewCategory() {
        $('#title').text('Tambah Supplier');
        $('#addKategoriModal').modal('show');
        $('#dataKategoriForm input').removeAttr('readonly');
        $('#updateBtn').removeClass('d-none');
        $('#editBtn').addClass('d-none');
    }

    function emptyModal() {
        $('#dataKategoriForm input').attr('disabled');
        $('#dataKategoriForm input').val('');
        $('#editBtn, #updateBtn').addClass('d-none');
        $('#addKategoriModal').modal('hide');
    }

    function updateKategori(e) {
        e.preventDefault();
        let formData = {
            kode: $('#kode').val(),
            nama: $('#nama').val(),
            alamat: $('#alamat').val(),
            noTel: $('#noTel').unmask(),
            perwakilan: $('#petugas').val()
        };
        const url = '{{route("updateKategori")}}';
        asyncAjaxUpdate(url,'POST',formData).then(() => {
            emptyModal();
            location.reload();
        }).catch((error) => {
            errorAlert(error);
        });
    }


    function openEditCategoryModal(params){
        let data = params.data[0];
        $('#addKategoriModal').modal('show');
        $('#title').text('Detail data Supplier '+data.golongan);
        $('input#kategori').val(data.golongan);
        $('input#keterangan').val(data.keterangan);
        $('#updateBtn').addClass('d-none');
        $('#editBtn').removeClass('d-none');
        $('#dataKategoriForm').find('input').attr('readonly','readonly');
    }

    function editCategory(id){
        let parent = $(`#golongan${id}`);
        let url = parent.attr('data-link');
        asyncAjaxUpdate(url,'GET',null).then((response) => {
            openEditCategoryModal(response);
        }).catch((error) => {
            console.log(error);
        });
    }

    function deleteCategory(golongan) {
        decisionAlert('Konfirmasi','Apakah anda yakin akan menghapus kategori ini ?','Kategori tetap disimpan').then((result) => {
            continueDelete();
        }).catch((error) => {
            console.log('canceled');
        });

        function continueDelete(){
            const url = `/administrator/kategori/${golongan}`;
            asyncAjaxUpdate(url,'DELETE', null).then((response) => {
                successAlert(response.message);
                setTimeout(() => {
                    location.reload();
                }, 1000);
            }).catch((error) => {
                errorAlert(error);
            });
        }

    }
</script>
@endpush
