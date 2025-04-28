<?php 
session_start();

// Jika member sudah login, tidak boleh kembali ke halaman login kecuali logout
if(isset($_SESSION["signIn"])) {
  header("Location: ../../DashboardMember/dashboardMember.php");
  exit;
}

require "../../loginSystem/connect.php";

if(isset($_POST["signIn"])) {
  // Sanitasi input untuk menghindari SQL Injection
  $nama = strtolower(mysqli_real_escape_string($connect, $_POST["nama"]));
  $npm = mysqli_real_escape_string($connect, $_POST["npm"]);
  $password = $_POST["password"];

  // Query untuk memeriksa username dan npm
  $result = mysqli_query($connect, "SELECT * FROM member WHERE nama = '$nama' AND npm = '$npm'");

  if(mysqli_num_rows($result) === 1) {
    // Cek password
    $pw = mysqli_fetch_assoc($result);
    if(password_verify($password, $pw["password"])) {
      // SET SESSION
      $_SESSION["signIn"] = true;
      $_SESSION["member"]["nama"] = $nama;
      $_SESSION["member"]["npm"] = $npm;
      header("Location: ../../DashboardMember/dashboardMember.php");
      exit;
    }
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
  <title>Member Sign In</title>
</head>
<body>
  <div class="login-card">
    <div class="card-left">
      <h2>Welcome Back!</h2>
      <p>Masuk sebagai member untuk melanjutkan akses ke dashboard peminjaman.<br>Belum punya akun? Daftar sekarang!</p>
      <a href="sign_up.php" class="btn btn-outline-light mt-3">Sign Up</a>
    </div>
    <div class="card-right">
      <div class="text-center">
        <img src="../../assets/memberLogo.png" alt="memberLogo" class="logo-img rounded-circle">
        <h4 class="fw-bold mb-3">Member Sign In</h4>
      </div>
      <form action="" method="post" class="needs-validation" novalidate>
        <div class="mb-3">
          <label for="namaInput" class="form-label">Nama Lengkap</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
            <input type="text" class="form-control" name="nama" id="namaInput" required>
            <div class="invalid-feedback">Masukkan nama anda!</div>
          </div>
        </div>
        <div class="mb-3">
          <label for="npmInput" class="form-label">NPM</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fa-solid fa-hashtag"></i></span>
            <input type="text" class="form-control" name="npm" id="npmInput" required pattern="[a-zA-Z0-9]+" title="Hanya huruf dan angka yang diperbolehkan">
            <div class="invalid-feedback">Masukkan NPM anda!</div>
          </div>
        </div>
        <div class="mb-3">
          <label for="passwordInput" class="form-label">Password</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
            <input type="password" class="form-control" name="password" id="passwordInput" required>
            <div class="invalid-feedback">Masukkan password anda!</div>
          </div>
        </div>
        <div class="d-grid gap-2">
          <button class="btn btn-custom text-white" type="submit" name="signIn">Sign In</button>
          <a class="btn btn-outline-secondary" href="../link_login.html">Batal</a>
        </div>
      </form>
      <?php if(isset($error)): ?>
        <div class="alert alert-danger mt-3 text-center" role="alert">
          Nama / NPM / Password tidak sesuai!
        </div>
      <?php endif; ?>
    </div>
  </div>

  <script>
    (() => {
      'use strict'
      const forms = document.querySelectorAll('.needs-validation')
      Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
          if (!form.checkValidity()) {
            event.preventDefault()
            event.stopPropagation()
          }
          form.classList.add('was-validated')
        }, false)
      })
    })()
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
