function submitNewObat(event, url) {
    event.preventDefault();
    const csrfToken = $('meta[name="csrf-token"]').attr("content");

    var fileInput = $("#image");
    var formData = new FormData();
    formData.append("kode", $("#kodeObat").val());
    formData.append("namaProduk", $("#namaObat").val());
    formData.append("golongan", JSON.stringify($("#golongan").val()));
    formData.append("satuan", $("#satuan").val());
    formData.append("stok", $("#stok").val());
    formData.append("supplier", $("#supplier").val());
    formData.append("expDate", $("#expDate").val());

    if (fileInput.files.length > 0) {
        formData.append("image", fileInput.files[0]);
    } else {
        const existingImageUrl = $("#previewImg").attr("src");
        formData.append("image", existingImageUrl);
    }

    $.ajax({
        url: url,
        method: "POST",
        data: formData,
        headers: {
            "X-CSRF-TOKEN": csrfToken,
        },
        contentType: false, // Set contentType ke false
        cache: false,
        processData: false,
        success: function (response) {
            $("#createNewObatForm")[0].reset();
            $("#golongan").val(null).trigger("change");
            $("#golongan").select2();
            successAlert("Data Produk Berhasil disimpan");
            undoChanges();
        },
        error: function (xhr, status, error) {
            console.error("Error:", error);
            console.log("Response:", xhr.responseText);
        },
    });
}

function randomized(event) {
    event.preventDefault();
    const kode = Math.floor(Math.random() * 99999);
    $("#kodeObat").val(`PRD-${kode}`);
}

function undoChanges() {
    $('#image').val("");
    $('.img-preview').hide();
    // document.getElementById("image").value = "";
    // document.querySelector(".img-preview").style.display = "none";
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
