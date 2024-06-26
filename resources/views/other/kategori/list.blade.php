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
        <a href="#" class="btn btn-sm btn-success ml-auto" onclick="addNewCategory()">Tambah Kategori</a>
    </div>
@endif
    <div class="xs-pd-20-10 pd-ltr-20">
        <div class="card-box mb-30 pt-3">
            <div class="pb-20 container">
                <table class="data-table table nowrap" id="myKategoriTable">
                    <thead>
                        <tr>
                            <th class="table-plus datatable-nosort">No</th>
                            <th>Kategori</th>
                            <th>Keterangan</th>
                            {{-- <th>Jumlah Produk</th> --}}
                            <th class="">Dibuat Pada</th>
                            <th class="datatable-nosort">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($kategori as $k)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$k->golongan}}</td>
                                <td>{{$k->keterangan}}</td>
                                <td>{{$k->created_at->format('d F Y')}}</td>
                                <td>
                                    <button class="btn btn-sm btn-success" onclick="editCategory('{{$k->id}}')" id="golongan{{$k->id}}" data-link="{{route('kategori.show',[$k->golongan])}}">Detail</button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteCategory('{{$k->id}}')" id="golongan{{$k->id}}" data-link="{{route('kategori.show',[$k->golongan])}}">Delete</button>
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
                            <label class="font-weight-bold d-flex">Kategori</label>
                            <input class="form-control" name="" value="" type="text" id="kategori" readonly
                                required />
                        </div>
                        <div class="form-group mb-4">
                            <label class="font-weight-bold">Keterangan</label>
                            <input class="form-control" name="" value="" type="text" id="keterangan"
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
<script>
    $().ready(function() {
        refreshTable();
    });

    function addNewCategory() {
        $('#title').text('Tambah Kategori');
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
            kategori: $('#kategori').val(),
            keterangan: $('#keterangan').val(),
        };
        const url = '{{route("updateKategori")}}';
        asyncAjaxUpdate(url,'POST',formData).then(() => {
            emptyModal();
            location.reload();
        }).catch((error) => {
            errorAlert(error);
        });
    }

    function refreshTable() {
        let i = 1;
        const url = '/obat/kategori/all';

        $.ajax({
            url: url,
            method: 'GET',
            success: function(response) {
                updateTable('#myKategoriTable', response.data, [{
                    render: function(data, type, row) {
                        return i++;
                    }
                }, {
                    data: 'golongan'
                }, {
                    data: 'keterangan'
                }, {
                    render: function(data, type, row) {
                        return `
                            <a class="text-danger" onclick="deleteKategori('${row.golongan }')"><i class="dw dw-delete-3"></i>
                                Delete</a>
                            `;
                    }
                }, ]);
            },
            error: function(error, xhr) {
                console.log(error);
                console.log(xhr.responseText);
            },
        });
    }

    function openEditCategoryModal(params){
        let data = params.data[0];
        $('#addKategoriModal').modal('show');
        $('#title').text('Detail data kategori '+data.golongan);
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
