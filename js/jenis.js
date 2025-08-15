// Edit/Update Jenis
function editJenis(id_jenis) {
  // Ambil data dari table row
  const row = document.querySelector(`#dataTable tr[data-jenis-id='${id_jenis}']`);
  if (!row) return;

  $("#idJenisInput").val(id_jenis); // hidden input untuk id

  // Ambil nilai dari tabel
  $("#namaTernak").val(row.cells[1].innerText);
  $("#kategoriSelect").val(row.dataset.kategoriId).trigger("change");
  $("#deskripsiTextarea").val(row.cells[3].innerText); // kolom keterangan

  // Ubah mode modal jadi update
  $("#modalSubmitBtn").val("update");
  $("#myModal .modal-title").text("Edit Jenis");
  $("#myModal").modal("show");

  $("#myModal").on("hidden.bs.modal", function () {
    $(this).find("form")[0].reset(); // Reset semua input di form modal
    $("#myModal .modal-title").text("Tambah Jenis Ternak");
  });
}

function toggleArrow(select) {
  const container = select.next(".select2-container");
  const val = select.val();
  if (val) {
    container.find(".select2-selection__arrow").hide();
  } else {
    container.find(".select2-selection__arrow").show();
  }
}

$(document).ready(function () {
  $(".select2-kategori")
    .select2({
      placeholder: "Pilih Kategori",
      allowClear: true,
      width: "100%",
    })
    .on("change", function () {
      toggleArrow($(this));
    });

  // Set visibility arrow pas load page
  $(".select2-kategori").each(function () {
    toggleArrow($(this));
  });

  $("#myModal").on("hidden.bs.modal", function () {
    console.log("Resetting form and select2 inside modal...");
    const form = $(this).find("form")[0];
    if (form) form.reset();
    $(this).find(".select2-kategori").val(null).trigger("change");
  });
});

// tambahkan sebelum $(document).ready atau di atas file
window.deleteJenis = function (id_jenis) {
  if (!confirm("Yakin ingin menghapus jenis ternak ini?")) return;
  // buat form POST dinamis supaya server (proses_jenis.php) dapat meng-redirect kembali
  const f = document.createElement("form");
  f.method = "POST";
  f.action = "proses_jenis.php";
  const inputAction = document.createElement("input");
  inputAction.type = "hidden";
  inputAction.name = "action";
  inputAction.value = "delete";
  const inputId = document.createElement("input");
  inputId.type = "hidden";
  inputId.name = "id_jenis";
  inputId.value = id_jenis;
  f.appendChild(inputAction);
  f.appendChild(inputId);
  document.body.appendChild(f);
  f.submit();
};
