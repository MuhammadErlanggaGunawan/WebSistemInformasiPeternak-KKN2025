$(document).ready(function () {
  // Inisialisasi Select2
  $(".select2-users").select2({
    placeholder: "Pilih User",
    allowClear: true,
    width: "100%",
  });
  $(".select2-ternak").select2({
    placeholder: "Pilih Jenis Ternak",
    allowClear: true,
    width: "100%",
  });

  // Fungsi untuk menghitung total ternak
  function updateTotal() {
    const betina = parseInt($("#jumlahBetina").val()) || 0;
    const jantan = parseInt($("#jumlahJantan").val()) || 0;
    $("#jumlahTotal").val(betina + jantan);
  }
  $("#jumlahBetina, #jumlahJantan").on("input", updateTotal);

  // Fungsi untuk mendapatkan tanggal dan waktu lokal
  function getLocalDateTime() {
    const now = new Date();
    now.setMinutes(now.getMinutes() - now.getTimezoneOffset()); // Konversi ke local time
    return now.toISOString().slice(0, 16); // Ambil format yyyy-MM-ddTHH:mm
  }

  // Logika saat modal ditampilkan
  $("#myModal").on("show.bs.modal", function (event) {
    const button = $(event.relatedTarget); // Tombol yang memicu modal
    const action = button.data("action") || "insert"; // Ambil action dari tombol, default 'insert'

    const modal = $(this);
    modal.find("form")[0].reset(); // Reset form setiap kali dibuka
    $(".select2-users, .select2-ternak").val(null).trigger("change");

    if (action === "edit") {
      modal.find(".modal-title").text("Edit Stok");
      modal.find("form").data("action", "update");

      const id_stok = button.data("id");
      const row = $(`#dataTable tr[data-id='${id_stok}']`);

      // Isi form dengan data dari baris tabel
      modal.find("#idStokInput").val(id_stok);
      modal.find("#idUserSelect").val(row.data("user-id")).trigger("change").prop("disabled", true);
      modal.find("#idJenisSelect").val(row.data("jenis-id")).trigger("change");
      modal.find("#jumlahJantan").val(row.find("td:eq(5)").text());
      modal.find("#jumlahBetina").val(row.find("td:eq(4)").text());
      modal.find("#keteranganTextarea").val(row.find("td:eq(7)").text());

      // Format tanggal untuk input datetime-local
      const rawDate = row.find("td:eq(8)").text().trim();
      if (rawDate && rawDate.length >= 16) {
        modal.find("#tanggalInput").val(rawDate.replace(" ", "T").slice(0, 16));
      } else {
        modal.find("#tanggalInput").val(getLocalDateTime());
      }

      updateTotal();
    } else {
      // Mode 'insert'
      modal.find(".modal-title").text("Tambah Stok");
      modal.find("form").data("action", "insert");
      modal.find("#idUserSelect").prop("disabled", false);
      modal.find("#tanggalInput").val(getLocalDateTime());

      // Disable user yang sudah punya stok
      $("#idUserSelect option").each(function () {
        $(this).prop("disabled", $(this).data("has-stok") == 1);
      });
    }
  });

  // Proses form submit via AJAX
  $("#myModal form").on("submit", function (e) {
    e.preventDefault();
    const form = $(this);
    const action = form.data("action");
    let formData = form.serialize();
    formData += "&action=" + action; // Tambahkan action ke data form

    $.post("proses_stok.php", formData)
      .done(function (response) {
        if (response.status === "success") {
          window.location.reload(); // Reload halaman untuk menampilkan alert
        } else {
          alert("Error: " + response.message);
        }
      })
      .fail(function (xhr) {
        alert("Request ke server gagal. Cek console untuk detail.");
        console.log(xhr.responseText);
      });
  });
});

// Fungsi untuk delete
function deleteStok(id_stok) {
  if (confirm("Yakin ingin menghapus stok ini?")) {
    $.post("proses_stok.php", { action: "delete", id_stok: id_stok })
      .done(function (response) {
        if (response.status === "success") {
          window.location.reload(); // Reload untuk menampilkan alert
        } else {
          alert("Error: " + response.message);
        }
      })
      .fail(function (xhr) {
        alert("Request ke server gagal. Cek console untuk detail.");
        console.log(xhr.responseText);
      });
  }
}
