function submitNewObat(event, url) {
    event.preventDefault();
    let fileInput = $("#createNewObatForm input[type=file]").get(0);
    let formData = new FormData();
    formData.append("kode", $("#kodeObat").val());
    formData.append("namaProduk", $("#namaObat").val());
    formData.append("golongan", JSON.stringify($("#golongan").val()));
    formData.append("satuan", $("#satuan").val());
    formData.append("stok", $("#stok").val());
    formData.append("supplier", $("#supplier").val());
    formData.append("expDate", $("#expDate").val());
    formData.append("harga", $("#harga").cleanVal());
    formData.append("deskripsi", $("#descriptionInput").val());

    if (fileInput.files.length > 0) {
        formData.append("image", fileInput.files[0]);
    } else {
        const existingImageUrl = $("#previewImg").attr("src");
        formData.append("image", existingImageUrl);
    }
    ajaxUpdate(url, "POST", formData);

    $("#createNewObatForm input").val('');
    $("#golongan").val(null).trigger("change");
    initSel2Tags('#golongan, #supplier');
    undoChanges();
    setTimeout(function() {
        window.location.href = "/apoteker/obat/list";
    }, 2000);
}

function randomized(event) {
    event.preventDefault();
    $("#kodeObat").val('PRD-'+randomString());
}

function undoChanges() {
    $("#image").val("");
    $(".img-preview").hide();
    $("#descriptionInput").val('');
}

function previewImage() {
    $(".img-preview").removeClass("d-none");
    const image = document.querySelector("#image");
    const imgPreview = document.querySelector(".img-preview");

    imgPreview.style.display = "flex";

    const oFReader = new FileReader();
    oFReader.readAsDataURL(image.files[0]);

    oFReader.onload = function (oFREvent) {
        imgPreview.src = oFREvent.target.result;
    };

    const isImage = image.value;
    if (isImage === "") {
        undoChanges();
    }
}
