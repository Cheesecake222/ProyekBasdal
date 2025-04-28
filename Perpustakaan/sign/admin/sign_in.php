<?php
session_start();

// Jika admin sudah login, tidak boleh kembali ke halaman login kecuali logout
if (isset($_SESSION["signIn"])) {
  header("Location: ../../DashboardAdmin/dashboardAdmin.php");
  exit;
}

require "../../loginSystem/connect.php";

if (isset($_POST["signIn"])) {
  $nama = strtolower($_POST["nama_admin"]);
  $password = $_POST["password"];

  $result = mysqli_query($connect, "SELECT * FROM admin WHERE nama_admin = '$nama' AND password = '$password'");
  if (mysqli_num_rows($result) === 1) {
    $_SESSION["signIn"] = true;
    $_SESSION["admin"]["nama_admin"] = $nama;
    header("Location: ../../DashboardAdmin/dashboardAdmin.php");
    exit;
  }
  $error = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="style.css" rel="stylesheet">
  <script src="https://kit.fontawesome.com/de8de52639.js" crossorigin="anonymous"></script>
  <title>Admin Sign In</title>
</head>
<body>
  <div class="login-card">
    <div class="card-left">
      <h2>Welcome Admin!</h2>
      <p>Masuk sebagai admin untuk mengelola konten dan data perpustakaan.</p>
      <a href="../link_login.html" class="btn btn-outline-light mt-3">Batal</a>
    </div>
    <div class="card-right">
      <div class="text-center">
        <img src="../../assets/adminLogo.png" alt="adminLogo" class="logo-img rounded-circle">
        <h4 class="fw-bold mb-3">Admin Sign In</h4>
      </div>
      <form action="" method="post" class="needs-validation" novalidate>
        <div class="mb-3">
          <label for="namaAdmin" class="form-label">Nama Lengkap</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
            <input type="text" class="form-control" name="nama_admin" id="namaAdmin" required>
            <div class="invalid-feedback">Masukkan nama admin!</div>
          </div>
        </div>
        <div class="mb-3">
          <label for="passwordAdmin" class="form-label">Password</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
            <input type="password" class="form-control" name="password" id="passwordAdmin" required>
            <div class="invalid-feedback">Masukkan password!</div>
          </div>
        </div>
        <div class="d-grid gap-2">
          <button class="btn btn-custom text-white" type="submit" name="signIn">Sign In</button>
        </div>
      </form>
      <?php if (isset($error)): ?>
        <div class="alert alert-danger mt-3 text-center" role="alert">
          Nama atau Password Salah!
        </div>
      <?php endif; ?>
    </div>
  </div>

  <script>
    (() => {
      'use strict';
      const forms = document.querySelectorAll('.needs-validation');
      Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
          if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
          }
          form.classList.add('was-validated');
        }, false);
      });
    })();
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>