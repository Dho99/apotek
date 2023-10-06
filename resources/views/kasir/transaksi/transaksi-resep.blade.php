@extends('layouts.main')
@section('content')
    <div class="xs-pd-20-10 pd-ltr-20">
        <div class="title pb-20">
            <h2 class="h3 mb-0">{{ $title }}</h2>
        </div>

        <div class="card-box mb-30">
            <div class="p-5">
                <form action="javascript:void(0)" onsubmit="submitResep(event);">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-3">
                            <label for="kode-obat">Kode Pasien</label>
                            <span class="float-right"></span>
                            <select id="kodePasien" class="custom-select2 form-control" style="width: 100%;" required
                                onchange="getDataByKode()">
                                <option value="">Cari Data Pasien</option>
                                @foreach ($pasiens as $item)
                                    <option value="{{ $item->kode }}">{{ $item->kode }} | {{ $item->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div id="inputAfterSearchKode">

                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

<script>
    function getDataByKode() {
        const kode = $('#kodePasien').val();
        if (kode !== "") {
            $.ajax({
                url: '/kasir/pasien/get/' + kode,
                method: 'GET',
                success: function(response) {
                    console.log(kode);
                    const data = response.data[0];
                    $('#inputAfterSearchKode').empty().append(`
                    <div class="row mb-3">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <label for="nama-obat">Nama Pasien</label>
                                <span class="float-right"></span>
                                <input type="text" name="nama-obat" value="${data.nama}" class="form-control" required disabled
                                    placeholder="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-3">
                                <label for="stock">Gejala yang dialami</label>
                                <span class="float-right"></span>
                                <textarea name="" class="form-control" id="gejala" cols="30" rows="10"></textarea>
                            </div>
                        </div>
                        <div class="row my-3">
                            <div class="col-xl-2 col-lg-6 col-md-12 col-sm-12 mb-3">
                                <a href="/kasir/transaksi/nonresep"
                                    onclick="alert('Perubahan yang anda Lakukan tidak Akan Disimpan !')"
                                    class="btn btn-secondary w-100">Kembali</a>
                            </div>
                            <div class="col-xl-2 col-lg-6 col-md-12 col-sm-12 ml-auto">
                                <button type="submit" id="submitStokBtn" class="btn btn-primary float-right  w-100">Simpan</button>
                            </div>
                        </div>
                    </div>
                    `);
                },
                error: function(error) {
                    console.log(error.message);
                }
            })
        } else {
            $('#inputAfterSearchKode').empty();
        }
    }

   function submitResep(e){
        e.preventDefault();
        let kodePasien = $('#kodePasien').val();
        let myForm = new FormData();
        myForm.append('kode', 'RES-'+randomString());
        myForm.append('kodePasien', kodePasien);
        myForm.append('gejala', $('#gejala').val());
        const url = '/apoteker/transaksi/resep/create';

        ajaxUpdate(url,'POST',myForm);
        setTimeout(() => {
            $('#kodePasien').val('').change();

        }, 1500);
   }
</script>
