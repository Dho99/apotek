@extends('layouts.main')
@section('plugins')
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
@endsection
@section('content')
    <div class="container bg-white rounded py-3 px-2 my-3">
        <form class="row d-inline-flex px-4" id="productForm">
            <div class="col-lg-6 col-md-6 col-12">
                <div class="form-group">
                    <label for="">Kode Produk</label>
                    <input type="text" class="form-control" name="productCode" id="productCode">
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-12">
                <div class="form-group">
                    <label for="">Nama Produk</label>
                    <input type="text" class="form-control" id="productName" name="productName">
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-12">
                <div class="form-group">
                    <label for="">Kategori Produk</label>
                    <select name="productCategory" id="productCategory" class="form-control" style="width: 100%;">
                        <option value="">Pilih Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ encrypt($category->id) }}">{{ $category->golongan }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-12">
                <div class="form-group">
                    <label for="">Supplier Produk</label>
                    <select name="supplierSelect" id="supplierSelect" class="form-control" style="width: 100%;">
                        <option value="">Pilih Supplier</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ encrypt($supplier->id) }}">{{ $supplier->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="d-lg-none d-block col-md-6 col-12">
                <div class="form-group">
                    <label for="">Tanggal Kadaluarsa</label>
                    <input type="date" class="form-control" id="expDate" name="expDate">
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-12">
                <div class="col-12 m-0 p-0 d-lg-block d-none">
                    <div class="form-group">
                        <label for="">Tanggal Kadaluarsa</label>
                        <input type="date" class="form-control" name="expDate" id="expDate">
                    </div>
                </div>
                <div class="form-group row ">
                    <label for="" class="col-12">Gambar Produk</label>
                    <div class="col-12">
                        <button type="button" class="w-100 btn btn-outline-success position-relative"
                            onclick="addImages()">Tambahkan
                            Gambar <span id="countImage">0</span>/5</button>
                    </div>
                </div>
            </div>

            <div class="col-lg-8 col-12">
                <div class="form-group">
                    <label for="">Deskripsi Produk</label>
                    <textarea name="" id="productDescription" class="form-control"></textarea>
                </div>
            </div>
            <div class="col-12 d-flex row w-100 m-0 p-0">
                <div class="col-3">
                    <button type="button" class="btn btn-secondary">Kembali</button>
                </div>
                <div class="col-3 ml-auto d-flex">
                    <button type="submit" id="submitterButton" class="btn btn-success ml-auto">Simpan</button>
                </div>
            </div>
        </form>
    </div>
    <div class="modal fade" id="productImageModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Gambar Produk</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('uploadFile', ['path' => 'product']) }}" class="dropzone"
                        id="my-awesome-dropzone"></form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success ml-auto">Simpan</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <script>
        $(function() {
            $('#productCategory, #supplierSelect').select2();
            inputMaskFormat('#buyPrice, #sellPrice');
            Dropzone.options.autoDiscover = false;
            // $('input, select, textarea').attr('required','required');
        });


        function addImages() {
            $('#productImageModal').modal('show');
        }

        let countImageUploaded = 0;
        let countImageEl = $('span#countImage');
        let productImage = [];
        let i = 0;

        Dropzone.options.myAwesomeDropzone = {
            init: function() {
                this.on("addedfile", function(file) {

                    var removeButton = Dropzone.createElement(
                        "<button class='btn btn-danger p-2 d-flex m-auto'>Hapus</button>");
                    var _this = this;

                    removeButton.addEventListener("click", function(e) {
                        countImageUploaded--;
                        $('#countImage').text(countImageUploaded);

                        e.preventDefault();
                        e.stopPropagation();

                        // Remove the file preview.
                        _this.removeFile(file);
                    });
                    file.previewElement.appendChild(removeButton);
                });

                this.on('removedfile', function(file) {
                    let rmvFile = "";
                    let rmvFileId = "";
                    for (f = 0; f < productImage.length; f++) {

                        if (productImage[f].fileName == file.name) {
                            rmvFile = productImage[f].serverFileName;
                            rmvFileId = f;
                        }

                    }
                    if (rmvFile) {
                        $.ajax({
                            url: "{{ route('deleteFile') }}",
                            type: "POST",
                            data: {
                                "filePath": rmvFile
                            }
                        }).done(() => {
                            productImage[rmvFileId].fileName = null;
                            productImage[rmvFileId].serverFileName = null;
                            console.log(productImage);
                        });
                    }
                });
            },
            maxFiles: 5,
            maxFilesize: 5,
            success: function(file, response) {
                countImageUploaded++;
                $('#countImage').text(countImageUploaded);
                productImage[i] = {
                    "serverFileName": response,
                    "fileName": file.name,
                    "fileId": i
                };
                i++;
                console.log(productImage);
            },
        }


        function getProductImagePaths()
        {
            let productImagePaths = [];
            for(let i = 0; i < productImage.length; i++){
                if(productImage[i].fileName !== null){
                    productImagePaths.push(productImage[i].serverFileName);
                }
            }
            return productImagePaths;
        }


        $("form").on("submit", function(event) {
            event.preventDefault();

            let isCanSubmit;

            const params = {
                productImagepaths: getProductImagePaths(),
                productCode: $('#productCode').val(),
                productName: $('#productName').val(),
                productCategory: $('#productCategory').val(),
                productSupplier: $('#supplierSelect').val(),
                expDate: $('#expDate').val(),
                productDescription: $('#productDescription').val(),
            };



            if(params.expDate !== ""){
                asyncAjaxUpdate('{{route("products.store")}}','POST',params).then((response) => {
                    console.log(response);
                }).catch((error) => {
                    errorAlert(Object.values(JSON.parse(error))[0]);
                });
            }else{
                errorAlert('Isi Semua data yang Diperlukan !');
            }

        });
    </script>
@endpush
