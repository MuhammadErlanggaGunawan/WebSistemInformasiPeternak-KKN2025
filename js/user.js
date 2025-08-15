// expose edit & delete to global so inline onclick works
window.editUser = function (id_user) {
  // redirect to form (implement edit handling di tambah_user.php untuk prefill)
  window.location.href = "tambah_user.php?id=" + encodeURIComponent(id_user);
};

window.deleteUser = function (id_user) {
  if (!confirm("Yakin ingin menghapus user ini?")) return;
  const f = document.createElement("form");
  f.method = "POST";
  f.action = "proses_user.php";

  const a = document.createElement("input");
  a.type = "hidden";
  a.name = "action";
  a.value = "delete";
  f.appendChild(a);

  const i = document.createElement("input");
  i.type = "hidden";
  i.name = "id_user";
  i.value = id_user;
  f.appendChild(i);

  document.body.appendChild(f);
  f.submit();
};
