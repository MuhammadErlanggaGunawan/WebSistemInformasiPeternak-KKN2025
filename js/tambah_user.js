function togglePassword(id) {
  const input = document.getElementById(id);
  const icon = document.getElementById("togglePasswordIcon_" + id);
  if (input.type === "password") {
    input.type = "text";
    icon.classList.remove("fa-eye");
    icon.classList.add("fa-eye-slash");
  } else {
    input.type = "password";
    icon.classList.remove("fa-eye-slash");
    icon.classList.add("fa-eye");
  }
}
document.getElementById("passwordConfirmUser").addEventListener("input", function () {
  const pass = document.getElementById("passwordUser").value;
  const confirmPass = this.value;

  if (pass && confirmPass && pass !== confirmPass) {
    this.setCustomValidity("Password tidak sama");
  } else {
    this.setCustomValidity("");
  }
});

$(document).ready(function () {
  $(".select2-role").select2({
    placeholder: "Pilih Role",
    width: "100%",
  });
});
