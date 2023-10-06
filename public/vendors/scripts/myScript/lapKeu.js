
function changeKategori() {
    let kategoriVal = $("#kategoriChanger").val();
    if (kategoriVal === "Debit") {
        $("#jumlahLabel").remove();
        $("#kategoriwrapper").after().append(`
                <div class="form-group mt-4" id="jumlahLabel">
                    <label class="font-weight-bold d-flex">Debit</label>
                    <input class="form-control" name="kode" value="" type="text" id="jumlah"
                        required />
                </div>
                `);
    } else if (kategoriVal === "Kredit") {
        $("#jumlahLabel").remove();
        $("#kategoriwrapper").after().append(`
                <div class="form-group mt-4" id="jumlahLabel">
                    <label class="font-weight-bold d-flex">Kredit</label>
                    <input class="form-control" name="kode" value="" type="text" id="jumlah"
                        required />
                </div>
                `);
    } else {
        $("#jumlahLabel").remove();
    }
    inputMaskFormat("#jumlah");
}

let kodeLaporan;

function refreshTable() {
    const kategori = $("#categoryKeuangan").val();
    const kode = kodeLaporan !== '' ? kodeLaporan : '';
    const month = $("#orByMo").val();
    const year = $("#orByYear").val();
    let text;
    if (kategori === "") {
        text = "Semua";
    } else {
        text = kategori;
    }
    $("#kategoriTitle").text(text);
    $.ajax({
        url: "/apoteker/laporan/keuangan/get",
        method: "GET",
        data: {
            kategori: kategori,
            kode: kode,
            month: month,
            year: year
        },
        success: function (response) {
            $("#saldoText").text(formatCurrency(response.saldo));
            if (response.modal === "true") {
                const hasil = response.data[0];
                showModal(hasil);
            } else {
                printable("#myKeuanganTable", response.data, [
                    {
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        render: function (data, type, row) {
                            return moment(`${row.created_at}`).format(
                                "DD/MM/YYYY"
                            );
                        }
                    },
                    {
                        data: 'user.nama'
                    },
                    {
                        data: 'keterangan'
                    },
                    {
                        data: 'kategori'
                    },
                    {
                        render: function (data, type, row, meta) {
                            return (
                                "Rp. " +
                                $.fn.dataTable.render
                                    .number(",", ".", 0, "")
                                    .display(`${row.jumlah}`)
                            );

                        }
                    },
                    {
                        data: null,
                        render: function (data, type, row) {
                            return `
                                <a class="text-primary mr-3 btn p-0" onclick="getDataKeuangan('${row.id}')">
                                    <i class="dw dw-eye"></i> Detail
                                </a>
                                `;
                        }
                    },
                ]);
            }
        },
        error: function (error, xhr) {
            console.log(error);
            console.log(xhr.responseText);
        },
    });
}

function getDataKeuangan(id) {
    kodeLaporan = id;
    refreshTable();
}

function tambahLaporan() {
    $("#title").text("Tambah Data Laporan");
    $("#tambahLaporanModal").modal("show");
    $("#myLaporanForm input").removeAttr("disabled");
    $("#createdAtrx, #tanggalTransaksi").remove();
    $("#kategoriwrapper, #createBtn").removeClass("d-none");
    $("#last").append(`
            <div class="form-group mt-4" id="kategoriwrapper">
                <label class="font-weight-bold d-flex">Kategori</label>
                <select name="" class="form-control" id="kategoriChanger" onchange="changeKategori()"
                    required>
                    <option value="">Pilih Kategori</option>
                    <option value="Kredit">Kredit</option>
                    <option value="Debit">Debit</option>
                </select>
            </div>
            `);
}

function showModal(hasil) {
    $("#saldowrapper").removeClass("d-none");
    $("#tambahLaporanModal").modal("show");
    $("#title").text("Detail Laporan");
    const createdAt = new Date(hasil.created_at).toISOString().substring(0, 10);
    $("#keterangan").val(hasil.keterangan);
    $("#last").append(`
            <div class="form-group mt-4" id="createdAtrx">
                <label class="font-weight-bold d-flex">Tanggal Transaksi</label>
                <input class="form-control" value="${createdAt}" type="date" id="tanggalTransaksi"
                    disabled required/>
            </div>
            `);
    if (hasil.kategori === "Debit") {
        $("#last").after().append(`
                <div class="form-group mt-4" id="jumlahLabel">
                    <label class="font-weight-bold d-flex">Debit</label>
                    <input class="form-control debit" name="kode" value="${hasil.jumlah}" type="text" id="jumlah" disabled
                        required />
                </div>
                `);
    } else {
        $("#last").after().append(`
                <div class="form-group mt-4" id="jumlahLabel">
                    <label class="font-weight-bold d-flex">Kredit</label>
                    <input class="form-control kredit" name="kode" value="${hasil.jumlah}" type="text" id="jumlah" disabled
                        required />
                </div>
                `);
    }
    inputMaskFormat("#jumlah");
}

function emptyModal() {
    kodeLaporan = '';
    $("#myLaporanForm input").val("");
    $("#myLaporanForm input").attr("disabled", "disabled");
    $(
        "#saldowrapper, #createdAtrx, #tanggalTransaksi, #kategoriwrapper, #jumlahLabel"
    ).remove();
    $("#tambahLaporanModal").modal("hide");
}

function createLaporan(event) {
    event.preventDefault();
    let url = "/apoteker/laporan/keuangan/create";
    let formData = new FormData();
    formData.append("keterangan", $("#keterangan").val());
    formData.append("kategori", $("#kategoriChanger").val());
    formData.append("jumlah", $("#jumlah").cleanVal());

    ajaxUpdate(url, "POST", formData);
}
